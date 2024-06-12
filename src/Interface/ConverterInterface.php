<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\Interface;

interface ConverterInterface
{
    public function build($object, string $format, array $context = []): mixed;

    public function builderForClassname(): string;

    public function isFormatSupported(string $format): bool;

    public function isClassSupported(string $classname): bool;
}
