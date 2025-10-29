<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers\Callable;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformers\TransformerInterface;

final class CallableToArrayTransformer implements TransformerInterface
{
    public static function inputType(): string { return 'callable';}

    public static function returnType(): string { return 'array';}

    /**
     * @param callable $input
     *
     * @throws UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        return ['callable'];
    }
}
