<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\Tests\Functional;

use JakubLech\Converter\ConverterClassAbstract;
use JakubLech\Converter\ConverterClassInterface;
use JakubLech\Converter\ConverterProvider;
use JakubLech\Converter\ConverterProviderInterface;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
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
        $exception = new \Exception('test', 123);
        $this->assertEquals(['error' => 'test', 'code' => 123], $this->sut->provide($exception)->convert($exception, 'array'));
        $this->assertEquals('{"error":"test","code":123}', $this->sut->provide($exception)->convert($exception, 'json'));

        $dateTime = new \DateTime('2021-01-01 12:00:00');
        $this->assertEquals('2021-01-01 12:00:00', $this->sut->provide($dateTime, true)->convert($dateTime, 'date-default', withFallback: true));
        $this->assertEquals('1609502400', $this->sut->provide($dateTime, true)->convert($dateTime, 'unixtime', withFallback: true));
        $this->assertEquals('2021-01-01', $this->sut->provide($dateTime, true)->convert($dateTime, 'date-custom', ['_format' => 'Y-m-d'], withFallback: true));
    }

    private function getExceptionConverter(ConverterProviderInterface $converterProvider): ConverterClassInterface
    {
        return new class($converterProvider) extends ConverterClassAbstract{

            public function __construct(\JakubLech\Converter\ConverterProviderInterface $converterProvider)
            {
                parent::__construct($converterProvider);
                $this->registerFormatHandler('array', fn (\Exception $class, array $context = []): array => $this->toArray($class, $context));
                $this->registerFormatHandler('json', fn (\Exception $class, array $context = []): string => $this->toJson($class, $context));

            }
            public function supportsClassName(): string
            {
                return \Exception::class;
            }

            public function toArray(\Exception $exception, array $context = []): array
            {
                return [
                    'error' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                ];
            }

            public function toJson(\Exception $exception, array $context = []): string
            {
                return json_encode($this->convert($exception, 'array', $context), $context['_flags'] ?? 0, $context['_depth'] ?? 512);
            }
        };
    }

    private function getDateTimeInterfaceConverter(ConverterProviderInterface $converterProvider): ConverterClassInterface
    {
        return new class($converterProvider) extends ConverterClassAbstract{

            public function __construct(\JakubLech\Converter\ConverterProviderInterface $converterProvider)
            {
                parent::__construct($converterProvider);
                $this->registerFormatHandler('date-default', fn (\DateTimeInterface $class, array $context = []): string => $class->format('Y-m-d H:i:s'));
                $this->registerFormatHandler('unixtime', fn (\DateTimeInterface $class, array $context = []): string => $class->format('U'));
                $this->registerFormatHandler('date-custom', fn (\DateTimeInterface $class, array $context = []): string => $class->format($context['_format']));
            }

            public function supportsClassName(): string
            {
                return \DateTimeInterface::class;
            }
        };
    }
}
