<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\TypesTransformer\GenericObject;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\TransformException;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformer;
use JakubLech\Transformer\TypesTransformer\TypesTransformerInterface;

final class ObjectToArrayUsingPublicPropertiesTypesTransformer implements TypesTransformerInterface
{
    public function __construct(private Transformer $transform)
    {
    }

    public static function inputType(): string
    {
        return 'object';
    }

    public static function returnType(): string
    {
        return 'array';
    }

    public static function priority(): int
    {
        return -1000;
    }

    /**
     * @param object $input
     *
     * @throws TransformException|UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        $array = (array)$input;

        array_walk_recursive($array, function (&$value) use ($context): void {
            if (is_object($value) || is_array($value)) {
                $value = ($this->transform)($value, 'array', $context);
            }
        });

        return $array;
    }
}
