<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\Tests\Unit;

use JakubLech\Converter\ConverterClassAbstract;
use JakubLech\Converter\ConverterProvider;
use PHPUnit\Framework\TestCase;

class ConverterProviderTest extends TestCase
{
    private ConverterProvider $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new ConverterProvider();
    }

    public function testIsSupportedTrueWhenConverterExists(): void
    {
        $converter = $this->createMock(ConverterClassAbstract::class);
        $converter->expects($this->any())
            ->method('supportsClassName')
            ->willReturn(\Exception::class);

        $this->sut->register($converter);
        $this->assertTrue($this->sut->isSupported(\Exception::class));
    }

    public function testProvideWillThrowExceptionWhenClassIsNotSupported(): void
    {
        $converter = $this->createMock(ConverterClassAbstract::class);
        $converter->expects($this->any())
            ->method('supportsClassName')
            ->willReturn(\Exception::class);

        $this->sut->register($converter);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Converter not found for class stdClass');
        $this->sut->provide(new \stdClass());
    }

    public function testProvideWithSupportedClass(): void
    {
        $converter = $this->createMock(ConverterClassAbstract::class);
        $converter->expects($this->any())
            ->method('supportsClassName')
            ->willReturn(\Exception::class);

        $converter->expects($this->once())
            ->method('convert')
            ->willReturn('test');

        $converter->expects($this->any())
            ->method('isClassSupported')
            ->willReturn(true);

        $this->sut->register($converter);
        $this->assertEquals('test', $this->sut->provide(new \Exception())->convert(new \Exception(), 'json'));
    }

    public function testProvideWithoutFallbackForInterfaceWillThrowException(): void
    {
        $converter = $this->createMock(ConverterClassAbstract::class);
        $converter->expects($this->any())
            ->method('supportsClassName')
            ->willReturn(\Throwable::class);

        $converter->expects($this->any())
            ->method('isClassSupported')
            ->willReturn(true);

        $this->sut->register($converter);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Converter not found for class Exception');
        $result = $this->sut->provide(new \Exception())->convert(new \Exception(), 'json');
    }

    public function testBuildWithFallbackWillBuildCorrectly(): void
    {
        $converter = $this->createMock(ConverterClassAbstract::class);
        $converter->expects($this->any())
            ->method('supportsClassName')
            ->willReturn(\Throwable::class);

        $converter->expects($this->once())
            ->method('convert')
            ->willReturn('test');

        $converter->expects($this->any())
            ->method('isClassSupported')
            ->willReturn(true);

        $this->sut->register($converter);

        $result = $this->sut->provide(new \Exception(), true)->convert(new \Exception(), 'json', withFallback: true);
        $this->assertEquals('test', $result);
    }
}
