<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers\GenericObject;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\TransformException;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Exception\UnsupportedTransformationException;
use JakubLech\Transformer\TransformHandler;
use JakubLech\Transformer\Transformers\TransformerInterface;

final class ObjectToArrayCompositeTransformer implements TransformerInterface
{
    public function __construct(private TransformHandler $transform){}

    public static function inputType(): string { return 'object';}

    public static function returnType(): string { return 'array';}

    /**
     * @param object $input
     *
     * @throws TransformException|UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        switch ($context['_strategyObjectToArray'] ?? 'useReflection') {
            case 'useReflection': $method = (new ObjectToArrayUsingReflectionTransformer($this->transform));

                break;

            case 'usePublicProperties': $method = (new ObjectToArrayUsingPublicPropertiesTransformer($this->transform));

                break;

            case 'useGetterBased': $method = (new ObjectToArrayUsingGettersTransformer($this->transform));

                break;

            case 'useJsonEncodeDecode': $method = (new ObjectToArrayUsingJsonEncodeDecodeTransformer());

                break;

            default:
                throw new UnsupportedTransformationException('Strategy Object to Array not found');
        }

        return $method($input, $context);
    }
}
