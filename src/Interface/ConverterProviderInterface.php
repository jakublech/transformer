<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\Interface;

use JakubLech\Converter\ConverterAbstract;

interface ConverterProviderInterface extends Buildable
{
    public function register(ConverterInterface $converter): void;

    public function find(string $classname): ?ConverterInterface;

    public function build($object, string $format, array $context = []): mixed;

}
