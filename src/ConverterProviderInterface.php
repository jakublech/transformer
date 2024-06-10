<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter;

use JakubLech\Converter\ConverterClassAbstract;

interface ConverterProviderInterface
{
    public function register(ConverterClassAbstract $converter): void;
    public function provide(object $classname, bool $withWithFallback = false): mixed;
}
