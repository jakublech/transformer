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
use JakubLech\Transformer\Exception\TransformException;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transform;
use IteratorAggregate;

final class IteratorAggregateToArrayTransformer implements TransformerInterface
{
    public function __construct(private Transform $transform)
    {
    }

    /**
     * @param IteratorAggregate $input
     * @throws TransformException | UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        $properties = iterator_to_array($input->getIterator());
        $result = [];
        foreach ($properties as $key => $property) {
            $result[$key] = is_object($property) ? ($this->transform)($property, $this::returnType()) : $property;
        }

        return $result;
    }

    public static function inputType(): string
    {
        return \IteratorAggregate::class;
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
