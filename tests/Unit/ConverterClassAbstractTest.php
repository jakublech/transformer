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
use JakubLech\Converter\Interface\ConverterProviderInterface;
use PHPUnit\Framework\TestCase;
use DateTimeInterface;
use Exception;

final class ConverterClassAbstractTest extends TestCase
{
    private ConverterAbstract $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new class() extends ConverterAbstract {
            protected const string CONVERTER_FOR_CLASSNAME = \DateTimeInterface::class;

            public function array(\DateTimeInterface $class, array $context = []): array
            {
                return ['test'];
            }
        };
    }

    public function testSupportsClassName(): void
    {
        $this->assertSame(\DateTimeInterface::class, $this->sut->builderForClassname());
    }

    public function testIsClassSupportedWillReturnTrue(): void
    {
        $this->assertTrue($this->sut->isClassSupported(\DateTimeImmutable::class));
    }

    public function testIsClassSupportedInterfaceWillReturnTrue(): void
    {
        $this->sut = new class() extends ConverterAbstract {
            protected const string CONVERTER_FOR_CLASSNAME = \DateTime::class;

            public function array(\DateTime $class, array $context = []): array
            {
                return ['test'];
            }
        };
        $this->assertTrue($this->sut->isClassSupported(\DateTime::class));
    }

    public function testIsClassSupportedWillReturnFalse(): void
    {
        $this->assertFalse($this->sut->isClassSupported(Exception::class));
    }

    public function testFormatIsNotSupported(): void
    {
        $this->assertFalse($this->sut->isFormatSupported('other-format'));
    }

    public function testFormatIsSupported(): void
    {
        $this->assertTrue($this->sut->isFormatSupported('array'));
    }

    public function testConvert(): void
    {
        $result = $this->sut->build(new \DateTimeImmutable(), 'array');
        $this->assertSame(['test'], $result);
    }
}
