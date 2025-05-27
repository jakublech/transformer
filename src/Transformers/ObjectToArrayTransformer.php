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
use ReflectionClass;

final class ObjectToArrayTransformer implements TransformerInterface
{
    public static function inputType(): string {return 'object';}
    public static function returnType(): string {return 'array';}
    public static function priority(): int {return -1000;}

    public function __construct(private Transform $transform){}

    /**
     * @throws TransformException | UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        /**
         * Possible strategies:
         * useReflection
         * usePublicProperties
         * useGetterBased
         * useJsonEncodeDecode
         */
        $method = $context['_strategyObjectToArray'] ?? 'useReflection';

        return $this->$method($input, $context);
    }

    public function useReflection(mixed $input, array $context = []): array
    {
        $result = [];
        $reflect = new ReflectionClass($input);

        foreach ($reflect->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($input);

            // Recursive transformation for nested objects and arrays
            $result[$property->getName()] = is_object($value) || is_array($value)
                ? ($this->transform)($value, 'array', $context)
                : $value;
        }

        return $result;
    }

    public function usePublicProperties(mixed $input, array $context = []): array
    {
        $array = (array)$input;

        array_walk_recursive($array, function (&$value) use ($context)  {
            if (is_object($value) || is_array($value)) {
                $value = ($this->transform)($value, 'array', $context);
            }
        });

        return $array;
    }

    public function useGetterBased(mixed $input, array $context = []): array
    {
        $array = [];
        foreach (get_class_methods($input) as $method) {
            if (str_starts_with($method, 'get')) {
                $key = lcfirst(substr($method, 3));
                $array[$key] = is_object($input->$method()) || is_array($input->$method())
                    ? ($this->transform)($input, 'array', $context)
                    : $input->$method();
            }
        }
        return $array;
    }

    public function useJsonEncodeDecode(mixed $input, array $context = []): array
    {
        $flags = $context['_flags'] ?? 0;
        $depth = $context['_depth'] ?? 512;

        $result = json_encode($input, $flags, $depth);
        if (JSON_ERROR_NONE !== json_last_error() || false === $result) {
            throw new TransformException('Can not transform array to json. ' . json_last_error_msg());
        }

        return (array) json_decode($result, true, $depth, $flags);
    }
}
