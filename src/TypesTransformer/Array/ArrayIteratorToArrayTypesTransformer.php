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
use JakubLech\Transformer\TransformHandler;
use JakubLech\Transformer\Transformers\TransformerInterface;
use ArrayIterator;

final class ArrayIteratorToArrayTransformer implements TransformerInterface
{
    public function __construct(private TransformHandler $transform){}

    public static function inputType(): string { return ArrayIterator::class;}

    public static function returnType(): string { return 'array';}

    /**
     * @param ArrayIterator $input
     *
     * @throws TransformException|UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        return $this->transform->transform((array) $input, 'array', $context);
    }
}
