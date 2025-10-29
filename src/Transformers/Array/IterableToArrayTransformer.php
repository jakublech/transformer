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

final class IterableToArrayTransformer implements TransformerInterface
{
    public function __construct(private TransformHandler $transform){}

    public static function inputType(): string { return 'iterable';}

    public static function returnType(): string { return 'array';}

    /**
     * @param iterable $input
     *
     * @throws TransformException|UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        return $this->transform->transform(iterator_to_array($input), 'array', $context);
    }
}
