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
use JakubLech\Transformer\Transform;
use JakubLech\Transformer\Transformers\TransformerInterface;
use ReflectionClass;

final class ObjectToArrayUsingPublicPropertiesTransformer implements TransformerInterface
{
    public static function inputType(): string {return 'object';}
    public static function returnType(): string {return 'array';}
    public static function priority(): int {return -1000;}

    public function __construct(private Transform $transform){}

    /**
     * @param  object $input
     * @throws TransformException | UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        $array = (array)$input;

        array_walk_recursive($array, function (&$value) use ($context)  {
            if (is_object($value) || is_array($value)) {
                $value = ($this->transform)($value, 'array', $context);
            }
        });

        return $array;
    }
}
