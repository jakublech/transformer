<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers\Throwable;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformers\Array\ArrayToJsonTransformer;
use JakubLech\Transformer\Transformers\TransformerInterface;
use Throwable;

final class ThrowableToJsonTransformer implements TransformerInterface
{
    /**
     * @param Throwable $input
     *
     * @throws UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): string
    {
        AssertInputType::strict($input, $this);

        return (new ArrayToJsonTransformer())(
            (new ThrowableToArrayTransformer())(
                $input,
                $context
            ),
            $context
        );
    }

    public static function inputType(): string
    {
        return Throwable::class;
    }

    public static function returnType(): string
    {
        return 'json';
    }

    public static function priority(): int
    {
        return -1000;
    }
}
