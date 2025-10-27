<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Assert;

use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\TypesTransformer\TypesTransformerInterface;

final class AssertInputType
{
    /** @throws UnsupportedInputTypeException */
    public static function strict(mixed $input, TypesTransformerInterface $transformer): void
    {
        $expectedType = $transformer::inputType();

        if ($input instanceof $expectedType) {
            return;
        }
        if (gettype($input) === $expectedType) {
            return;
        }
        if ('object' === $expectedType && is_object($input)) {
            return;
        }

        throw new UnsupportedInputTypeException(sprintf(
            'Expected %s, got %s in %s',
            $expectedType,
            is_object($input) ? $input::class : gettype($input),
            $transformer::class,
        ));
    }
}
