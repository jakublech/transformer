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
use JakubLech\Converter\ConverterProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConverterClassAbstractTest extends TestCase
{
    private ConverterClassAbstract $sut;
    protected function setUp(): void
    {
        parent::setUp();
        $converterProvider = $this->createMock(ConverterProviderInterface::class);
        $converter = new class($converterProvider) extends ConverterClassAbstract{
            protected const string CONVERTER_FOR_CLASSNAME = \DateTime::class;

        };
        $this->sut = new $converter($converterProvider);
    }

    public function testSupportsClassName()
    {
        $this->assertSame(\DateTime::class, $this->sut->supportsClassName());
    }

    public function testIsClassSupportedWillReturnTrue()
    {
        $this->assertTrue($this->sut->isClassSupported(\DateTime::class));
    }

    public function testIsClassSupportedWillReturnFalse()
    {
        $this->assertFalse($this->sut->isClassSupported(\Exception::class));
    }

    public function testIsClassSupportedWillReturnTrueWhenInterfaceFallback()
    {
        $converterProvider = $this->createMock(ConverterProviderInterface::class);
        $converter = new class($converterProvider) extends ConverterClassAbstract{
            protected const string CONVERTER_FOR_CLASSNAME = \DateTimeInterface::class;

        };
        $this->sut = new $converter($converterProvider);

        $this->assertTrue($this->sut->isClassSupported(\DateTime::class, true));
    }

    public function testFormatIsNotSupported()
    {
        $this->sut->registerFormatHandler('example-format', fn (object $class, array $context = []) => 'test response');
        $this->assertFalse($this->sut->isFormatSupported('other-format'));
    }

    public function testFormatIsSupported()
    {
        $this->sut->registerFormatHandler('example-format', fn (object $class, array $context = []) => 'test response');
        $this->assertTrue($this->sut->isFormatSupported('example-format'));
    }

    public function testConvert()
    {
        $this->sut->registerFormatHandler('example-format', fn (object $class, array $context = []) => 'test response');
        $result = $this->sut->convert(new \DateTime(), 'example-format');
        $this->assertSame('test response', $result);
    }

    public function testConvertWithFallback()
    {
        $converterProvider = $this->createMock(ConverterProviderInterface::class);
        $converter = new class($converterProvider) extends ConverterClassAbstract{
            protected const string CONVERTER_FOR_CLASSNAME = \DateTimeInterface::class;

        };
        $this->sut = new $converter($converterProvider);

        $this->sut->registerFormatHandler('example-format', fn (object $class, array $context = []) => 'test response');
        $result = $this->sut->convert(new \DateTime(), 'example-format', withFallback: true);
        $this->assertSame('test response', $result);
    }
}
