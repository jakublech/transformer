<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers;

use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use Stringable;

final class StringableToStringTransformer implements TransformerInterface
{
    public function __invoke(mixed $input, array $context = []): string
    {
        if (!is_a($input, Stringable::class, true)) {
            throw new UnsupportedInputTypeException();
        }

        return (string) $input;
    }

    public static function inputType(): string
    {
        return Stringable::class;
    }

    public static function returnType(): string
    {
        return 'string';
    }

    public static function priority(): int
    {
        return -1000;
    }
}
