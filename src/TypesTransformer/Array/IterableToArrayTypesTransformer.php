<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\TypesTransformer\Array;

use JakubLech\Transformer\Exception\TransformException;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformer;
use JakubLech\Transformer\TypesTransformer\TypesTransformerInterface;

final class IterableToArrayTypesTransformer implements TypesTransformerInterface
{
    public function __construct(private Transformer $transform)
    {
    }

    /**
     * @param iterable $input
     *
     * @throws TransformException|UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        return ($this->transform)(iterator_to_array($input), 'array', $context);
    }

    public static function inputType(): string
    {
        return 'iterable';
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
