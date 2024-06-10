<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter;

interface ConverterClassInterface
{
    public function supportsClassName(): string;
    public function registerFormatHandler(string $format, callable $handler): void;
    public function isFormatSupported(string $format): bool;
    public function convert(object $class, string $format, array $context = [], bool $withFallback = false): mixed;
}
