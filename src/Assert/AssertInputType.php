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
use JakubLech\Transformer\Transformers\TransformerInterface;

class AssertInputType
{
    /** @throws UnsupportedInputTypeException */
    public static  function strict(mixed $input, TransformerInterface $transformer): void
    {
        if (is_a($input, $transformer::inputType(), true)) {
            return;
        }
        $inputType = gettype($input);
        if ($inputType === $transformer::inputType()) {
            return;
        }
        throw new UnsupportedInputTypeException(sprintf('Expected %s, got %s in %s', $transformer::inputType(), $inputType, $transformer::class));
    }
}
