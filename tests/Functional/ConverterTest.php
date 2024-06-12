<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\Tests\Functional;

use JakubLech\Converter\ConverterAbstract;
use JakubLech\Converter\ConverterProvider;
use JakubLech\Converter\ConverterSelfRegisteredAbstract;
use JakubLech\Converter\Interface\ConverterInterface;
use JakubLech\Converter\Interface\ConverterProviderInterface;
use PHPUnit\Framework\TestCase;
use DateTimeInterface;
use Exception;

final class ConverterTest extends TestCase
{
    private ConverterProvider $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new ConverterProvider();
        $this->sut->register($this->getExceptionConverter($this->sut));
        $this->sut->register($this->getDateTimeInterfaceConverter($this->sut));
    }

    public function testGeneral(): void
    {
        $exception = new Exception('test', 123);
        $this->assertEquals(['error' => 'test', 'code' => 123], $this->sut->find($exception::class)->build($exception, 'array'));
        $this->assertEquals('{"error":"test","code":123}', $this->sut->find($exception::class)->build($exception, 'json'));

        $dateTime = new \DateTimeImmutable('2021-01-01 12:00:00');
        $this->assertEquals(['date' => '20210101', 'time' => '120000',], $this->sut->find($dateTime::class)->build($dateTime, 'array'));
    }

    private function getExceptionConverter(ConverterProviderInterface $converterProvider): ConverterInterface
    {
        return new class ($converterProvider) extends ConverterSelfRegisteredAbstract {

            public const CONVERTER_FOR_CLASSNAME = Exception::class;

            public function supportsClassName(): string
            {
                return Exception::class;
            }

            public function array(Exception $exception, array $context = []): array
            {
                return [
                    'error' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                ];
            }

            public function json(Exception $exception, array $context = []): string
            {
                return json_encode($this->build($exception, 'array', $context), $context['_flags'] ?? 0, $context['_depth'] ?? 512);
            }
        };
    }

    private function getDateTimeInterfaceConverter(ConverterProviderInterface $converterProvider): ConverterInterface
    {
        return new class () extends ConverterAbstract {

            public const CONVERTER_FOR_CLASSNAME = DateTimeInterface::class;

            public function array(DateTimeInterface $dateTime, array $context = []): array
            {
                return [
                    'date' => $dateTime->format('Ymd'),
                    'time' => $dateTime->format('His'),
                ];
            }

        };
    }
}
