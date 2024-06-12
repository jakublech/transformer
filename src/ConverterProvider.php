<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter;

use JakubLech\Converter\Interface\ConverterInterface;
use JakubLech\Converter\Interface\ConverterProviderInterface;
use RuntimeException;

final class ConverterProvider implements ConverterProviderInterface
{
    private array $buildersByClassName = [];
    public function register(ConverterInterface $converter): void
    {
        $this->buildersByClassName[$converter->builderForClassname()] = $converter;
    }

    public function find(string $classname): ?ConverterInterface
    {
        if (isset($this->buildersByClassName[$classname])) {
            return $this->buildersByClassName[$classname];
        }

        foreach ($this->buildersByClassName as $supportedClassName => $builder) {
            if (is_subclass_of($classname, $supportedClassName)) {
                return $builder;
            }
        }

        return null;
    }

    public function build($object, string $format, array $context = []): mixed
    {
        $builder = $this->find($object::class);
        if (null === $builder) {
            throw new RuntimeException('No builder found for class ' . get_class($object));
        }

        return $builder->build($object, $format, $context);
    }
}
