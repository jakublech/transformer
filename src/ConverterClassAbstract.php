<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter;

use RuntimeException;

class ConverterClassAbstract implements ConverterClassInterface
{
    protected const string CONVERTER_FOR_CLASSNAME = '';
    private array $formatHandlers = [];
    public function __construct(private ConverterProviderInterface $converterProvider)
    {
        if ('' === $this->supportsClassName()) {
            throw new RuntimeException('CONVERTER_FOR_CLASSNAME must be set to classname which builder is designed for');
        }
        $this->converterProvider->register($this);
        $this->registerFormatHandler('null', fn ($class, array $context = []): null => null);
    }

    public function supportsClassName(): string
    {
        return static::CONVERTER_FOR_CLASSNAME;
    }

    public function isClassSupported(string $classname, bool $withFallback = false): bool
    {
        if ($classname === static::supportsClassName()) {
            return true;
        }

        if (true === $withFallback && null !== $this->findFallbackClasses($classname)) {
            return true;
        }
        return false;
    }

    public function registerFormatHandler(string $format, callable $handler): void
    {
        $this->formatHandlers[$format] = $handler;
    }

    public function isFormatSupported(string $format): bool
    {
        return isset($this->formatHandlers[$format]);
    }

    public function convert(object $class, string $format, array $context = [], bool $withFallback = false): mixed
    {
        if (false === $this->isFormatSupported($format)) {
            throw new RuntimeException('Format ' . $format . ' is not supported for className '.static::CONVERTER_FOR_CLASSNAME);
        }

        if (true === $this->isClassSupported($class::class)) {
            return $this->formatHandlers[$format]($class, $context);
        }

        if (true === $withFallback && null !== $this->findFallbackClasses($class::class)) {
            return $this->formatHandlers[$format]($class, $context);
        }

        throw new RuntimeException('Converter '.static::class.' does not support class ' . $class::class);
    }

    private function findFallbackClasses(string $classname): ?string
    {
        $fallbackClasses = array_merge($this->getAbstractClasses($classname), class_implements($classname));
        foreach ($fallbackClasses as $fallbackClass) {
            if ($this->isClassSupported($fallbackClass)) {
                return $fallbackClass;
            }
        }

        return null;
    }

    private function getAbstractClasses(string $classname): array
    {
        $abstractClasses = [];
        $reflectionClass = new \ReflectionClass($classname);

        while ($parent = $reflectionClass->getParentClass()) {
            if ($parent->isAbstract()) {
                $abstractClasses[] = $parent->getName();
            }
            $reflectionClass = $parent;
        }

        return $abstractClasses;
    }
}
