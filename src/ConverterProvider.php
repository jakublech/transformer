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
use \ReflectionClass;

class ConverterProvider implements ConverterProviderInterface
{
    /**
     * @var ConverterClassInterface[]
     */
    private array $convertersByClassName = [];

    public function register(ConverterClassInterface $converter): void
    {
        $this->convertersByClassName[$converter->supportsClassName()] = $converter;
    }

    public function isSupported(string $classname, bool $withFallback = false): bool
    {
        if (isset($this->convertersByClassName[$classname])) {
            return true;
        }

        if ($withFallback && null !== $this->findFallbackClass($classname)) {
            return true;
        }

        return false;
    }

    public function provide(object $classname, bool $withFallback = false): ConverterClassInterface
    {
        if (true === $this->isSupported($classname::class)) {
            return $this->convertersByClassName[$classname::class];
        }

        if (true === $withFallback && null !== $fallbackClass = $this->findFallbackClass($classname::class)) {
            return $this->convertersByClassName[$fallbackClass];
        }

        throw new RuntimeException('Converter not found for class ' . $classname::class);
    }

    private function findFallbackClass(string $classname): ?string
    {
        $fallbackClasses = array_merge($this->getAbstractClasses($classname), class_implements($classname));
        foreach ($fallbackClasses as $fallbackClass) {
            if ($this->isSupported($fallbackClass)) {
                return $fallbackClass;
            }
        }

        return null;
    }

    private function getAbstractClasses(string $classname): array
    {
        $abstractClasses = [];
        $reflectionClass = new ReflectionClass($classname);

        while ($parent = $reflectionClass->getParentClass()) {
            if ($parent->isAbstract()) {
                $abstractClasses[] = $parent->getName();
            }
            $reflectionClass = $parent;
        }

        return $abstractClasses;
    }
}
