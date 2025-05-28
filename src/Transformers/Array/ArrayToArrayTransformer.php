<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers\Array;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\TransformException;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transform;
use JakubLech\Transformer\Transformers\TransformerInterface;

/** Transforms nested arrays */
final class ArrayToArrayTransformer implements TransformerInterface
{
    public function __construct(private Transform $transform)
    {
    }

    /**
     * Transforms nested arrays
     * @param array $input
     * @throws TransformException | UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        return array_map(function ($value) use ($context) {
            return is_object($value) || is_array($value)
                ? ($this->transform)($value, 'array', $context)
                : $value;
        }, $input);
    }

    public static function inputType(): string
    {
        return 'array';
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
