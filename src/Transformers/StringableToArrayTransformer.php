<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use Stringable;

final class StringableToArrayTransformer implements TransformerInterface
{
    /**
     * @param Stringable $input
     * @throws UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): string
    {
        AssertInputType::strict($input, $this);

        return (string) $input;
    }

    public static function inputType(): string
    {
        return Stringable::class;
    }

    public static function returnType(): string
    {
        return 'array';
    }

    public static function priority(): int
    {
        return -1000;
    }
}
