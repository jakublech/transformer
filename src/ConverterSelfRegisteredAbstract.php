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
use JakubLech\Converter\Interface\ConverterProviderInterface;

abstract class ConverterSelfRegisteredAbstract extends ConverterAbstract implements Buildable
{
    public function __construct(ConverterProviderInterface $converterProvider)
    {
        $converterProvider->register($this);
    }
}
