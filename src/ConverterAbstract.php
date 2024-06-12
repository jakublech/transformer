<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter;

use JakubLech\Converter\Interface\Buildable;
use JakubLech\Converter\Interface\ConverterInterface;
use RuntimeException;

abstract class ConverterAbstract implements Buildable, ConverterInterface
{
    protected const CONVERTER_FOR_CLASSNAME = '';
    public function build($object, string $format, array $context = []): mixed
    {
        $this->assertBuilderForClassnameIsSet();
        $this->assertClassSupported($object::class);
        $this->assertFormatSupported($format);

        return $this->{$format}($object, $context);
    }

    public function builderForClassname(): string
    {
        return static::CONVERTER_FOR_CLASSNAME;
    }

    public function isFormatSupported(string $format): bool
    {
        return method_exists($this, $format);
    }

    public function isClassSupported(string $classname): bool
    {
        return $classname === static::builderForClassname()
            || is_a($classname, static::builderForClassname())
            || is_subclass_of($classname, static::builderForClassname());
    }

    private function assertBuilderForClassnameIsSet(): void
    {
        if ('' === static::CONVERTER_FOR_CLASSNAME) {
            throw new RuntimeException('BUILDER_FOR_CLASSNAME constant must be defined in ' . get_class($this));
        }
    }

    protected function assertClassSupported(string $classname): void
    {
        if (false === $this->isClassSupported($classname)) {
            throw new RuntimeException('Class ' . get_class($classname) . ' is not supported for className ' . static::builderForClassname());
        }
    }

    protected function assertFormatSupported(string $format): void
    {
        if (false === $this->isFormatSupported($format)) {
            throw new RuntimeException('Format ' . $format . ' is not supported for className ' . static::builderForClassname());
        }
    }
}
