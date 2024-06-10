<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter;

use JakubLech\Converter\Converter\ConverterClassAbstract;
use RuntimeException;
use \ReflectionClass;

class Builder implements BuilderInterface
{
    /**
     * @var ConverterClassAbstract[]
     */
    private array $convertersByClassName = [];

    public function supportClassnameWithConverter(string $classname, ConverterClassAbstract $converter): void
    {
        $this->convertersByClassName[$classname] = $converter;
    }

    public function isSupported(string $classname): bool
    {
        return isset($this->convertersByClassName[$classname]);
    }

    public function build(object $class, string $format, array $context = []): mixed
    {
        if ($this->isSupported($class::class) === false) {
            throw new RuntimeException('Builder not found for class ' . $class::class);
        }

        return $this->convertersByClassName[$class::class]->convertClassToFormat($class, $format, $context);
    }

    public function buildWithFallback(object $class, string $format, array $context = []): mixed
    {
        if ($this->isSupported($class::class)) {
            return $this->convertersByClassName[$class::class]->convertClassToFormat($class, $format, $context);
        }

        foreach ($this->getAbstractClasses($class) as $abstractClass) {
            if ($this->isSupported($abstractClass)) {
                return $this->convertersByClassName[$abstractClass]->convertClassToFormat($class, $format, $context);
            }
        }

        foreach (class_implements($class::class) as $interface) {
            if ($this->isSupported($interface)) {
                return $this->convertersByClassName[$interface]->convertClassToFormat($class, $format, $context);
            }
        }

        throw new RuntimeException('Builder not found for class ' . $class::class);
    }

    private function getAbstractClasses(object $object): array
    {
        $abstractClasses = [];
        $reflectionClass = new ReflectionClass($object);

        while ($parent = $reflectionClass->getParentClass()) {
            if ($parent->isAbstract()) {
                $abstractClasses[] = $parent->getName();
            }
            $reflectionClass = $parent;
        }

        return $abstractClasses;
    }
}
