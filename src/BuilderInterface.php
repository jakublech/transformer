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

interface BuilderInterface
{
    public function supportClassnameWithConverter(string $classname, ConverterClassAbstract $converter): void;
    public function build(object $class, string $format, array $context = []): mixed;
}
