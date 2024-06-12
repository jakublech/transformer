<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\Tests\Unit;

use JakubLech\Converter\ConverterAbstract;
use JakubLech\Converter\ConverterProvider;
use PHPUnit\Framework\TestCase;
use Exception;
use RuntimeException;
use Throwable;
use stdClass;

final class ConverterProviderTest extends TestCase
{
    private ConverterProvider $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new ConverterProvider();
    }

    public function testFindWhenConverterNotExists(): void
    {
        $this->assertNull($this->sut->find(Exception::class));
    }

    public function testFindWhenConverterExists(): void
    {
        $converter = $this->createMock(ConverterAbstract::class);
        $converter->expects($this->any())
            ->method('builderForClassname')
            ->willReturn(Exception::class);

        $this->sut->register($converter);
        $this->assertInstanceOf(ConverterAbstract::class, $this->sut->find(Exception::class));
    }

    public function testFindWhenConverterSupportInterface(): void
    {
        $converter = $this->createMock(ConverterAbstract::class);
        $converter->expects($this->any())
            ->method('builderForClassname')
            ->willReturn(\Throwable::class);

        $this->sut->register($converter);
        $this->assertInstanceOf(ConverterAbstract::class, $this->sut->find(Exception::class));
    }

    public function testBuildWillThrowExceptionWhenClassIsNotSupported(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No builder found for class stdClass');
        $this->sut->build(new stdClass(), 'format', []);
    }

    public function testProvideWithSupportedClass(): void
    {
        $converter = new class() extends ConverterAbstract {
            protected const string CONVERTER_FOR_CLASSNAME = \DateTimeInterface::class;

        public function array(\DateTimeInterface $class, array $context = []): array
        {
            return ['test'];
        }
        };

        $this->sut->register($converter);
        $result = $this->sut->build(new \DateTime(), 'array', []);
        $this->assertSame(['test'], $result);
    }
}
