<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Tests\Unit;

use JakubLech\Transformer\TransformHandler as Transform;
use JakubLech\Transformer\Transformers\Array\ArrayIteratorToArrayTransformer;
use JakubLech\Transformer\Transformers\Array\ArrayToArrayTransformer;
use JakubLech\Transformer\Transformers\Array\ArrayToJsonTransformer;
use JakubLech\Transformer\Transformers\Array\ArrayToStdClassTransformer;
use JakubLech\Transformer\Transformers\Array\IterableToArrayTransformer;
use JakubLech\Transformer\Transformers\Array\IteratorAggregateToArrayTransformer;
use JakubLech\Transformer\Transformers\Callable\CallableToArrayTransformer;
use JakubLech\Transformer\Transformers\Callable\ClosureToArrayTransformer;
use JakubLech\Transformer\Transformers\DateTime\DateTimeInterfaceToArrayTransformer;
use JakubLech\Transformer\Transformers\GenericObject\ObjectToArrayCompositeTransformer;
use JakubLech\Transformer\Transformers\GenericObject\ObjectToJsonTransformer;
use JakubLech\Transformer\Transformers\GenericObject\StdClassToArray;
use JakubLech\Transformer\Transformers\Json\JsonSerializableToArray;
use JakubLech\Transformer\Transformers\Stringable\StringableToArrayTransformer;
use JakubLech\Transformer\Transformers\Throwable\ThrowableToArrayTransformer;
use JakubLech\Transformer\Transformers\Throwable\ThrowableToJsonTransformer;
use JakubLech\Transformer\TransformHandlerFactory;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
final class ComplexDtoTransformTest extends TestCase
{
    private Transform $sut;
    private ArrayToJsonTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = TransformHandlerFactory::defaultPhpNativeTypesTransformHandler();
    }

    public function testComplexDtoExampleToArrayUsingReflection(): void
    {
        $testDto = ComplexDtoExample::createFullyPopulated();
        $context = ['_strategyObjectToArray' => 'useReflection'];
        $resultReflection = $this->sut->transform($testDto, 'array', $context);
        $resultDefault = $this->sut->transform($testDto, 'array');

        $expected = [
            'id' => 123,
            'name' => 'Test DTO',
            'price' => 19.99,
            'isActive' => true,
            'nullableString' => null,
            'tags' => ['php', 'test', 'transformer'],
            'createdAt' => [
                'date' => '2025-05-27 18:30:37.000000',
                'timezone' => 'UTC',
            ],
            'updatedAt' => [
                'date' => '2025-05-28 19:31:35.000000',
                'timezone' => 'UTC',
            ],
            'parent' => null,
            'child' => null,
            'address' => [
                'street' => 'Main St',
                'city' => 'New York',
                'zip' => '10001', // Private property accessed via getter
            ],
            'products' => [
                [
                    'sku' => 'PROD-123',
                    'price' => 29.99,
                    'attributes' => ['color' => 'red'],
                ],
                [
                    'sku' => 'PROD-456',
                    'price' => 39.99,
                    'attributes' => ['size' => 'L'],
                ],
            ],
            'categories' => [
                1 => [
                    'id' => 0,
                    'name' => 'Root',
                    'parent' => [
                        'id' => 1,
                        'name' => 'Child',
                        'parent' => null,
                    ],
                ],
            ],
            'untypedProperty' => 'untyped value',
            'metadata' => [
                'key' => 'value',
            ],
            'stringableObject' => 'stringable',
            'jsonSerializableObject' => [
                'json' => 'data',
            ],
            'iterableProperty' => [1, 2, 3],
            'closureProperty' => ['Closure'], // Typically excluded or shown as '[Closure]'
            'exception' => ['error' => 'some exception', 'code' => 404],
            'privateProperty' => 'private value',
            'protectedArray' => ['protected' => 'data'],
        ];

        $this->assertSame($expected, $resultReflection);
        $this->assertSame($expected, $resultDefault);
    }

    public function testComplexDtoExampleToArrayUsingPublicProperties(): void
    {
        $testDto = ComplexDtoExample::createFullyPopulated();
        $context = ['_strategyObjectToArray' => 'usePublicProperties'];
        $result = $this->sut->transform($testDto, 'array', $context);

        $expected = [
            'id' => 123,
            'name' => 'Test DTO',
            'price' => 19.99,
            'isActive' => true,
            'nullableString' => null,
            'tags' => ['php', 'test', 'transformer'],
            'createdAt' => [
                'date' => '2025-05-27 18:30:37.000000',
                'timezone' => 'UTC',
            ],
            'updatedAt' => [
                'date' => '2025-05-28 19:31:35.000000',
                'timezone' => 'UTC',
            ],
            'parent' => null,
            'child' => null,
            'address' => [
                'street' => 'Main St',
                'city' => 'New York',
                "\0JakubLech\\Transformer\\Tests\\Unit\\AddressDto\0zip" => '10001', // Private property accessed via getter
            ],
            'products' => [
                [
                    'sku' => 'PROD-123',
                    'price' => 29.99,
                    'attributes' => ['color' => 'red'],
                ],
                [
                    'sku' => 'PROD-456',
                    'price' => 39.99,
                    'attributes' => ['size' => 'L'],
                ],
            ],
            'categories' => [
                1 => [
                    'id' => 0,
                    'name' => 'Root',
                    'parent' => [
                        'id' => 1,
                        'name' => 'Child',
                        'parent' => null,
                    ],
                ],
            ],
            'untypedProperty' => 'untyped value', // Accessed via reflection or getter
            'metadata' => [
                'key' => 'value',
            ],
            'stringableObject' => 'stringable',
            'jsonSerializableObject' => [
                'json' => 'data',
            ],
            'iterableProperty' => [1, 2, 3],
            'closureProperty' => ['Closure'], // Typically excluded or shown as '[Closure]'
            'exception' => ['error' => 'some exception', 'code' => 404],
            "\0JakubLech\\Transformer\\Tests\\Unit\\ComplexDtoExample\0privateProperty" => 'private value',
            "\0JakubLech\\Transformer\\Tests\\Unit\\ComplexDtoExample\0protectedArray" => ['protected' => 'data'],
        ];

        $this->assertEquals($expected, $result);
    }

    public function testComplexDtoExampleToArrayUsingGetterBased(): void
    {
        $testDto = ComplexDtoExample::createFullyPopulated();
        $context = ['_strategyObjectToArray' => 'useGetterBased'];
        $result = $this->sut->transform($testDto, 'array', $context);

        $expected = [
            'privateProperty' => 'private value',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testComplexDtoExampleToArrayUsingJsonEncodeDecode(): void
    {
        $testDto = ComplexDtoExample::createFullyPopulated();
        $context = ['_strategyObjectToArray' => 'useJsonEncodeDecode'];
        $result = $this->sut->transform($testDto, 'array', $context);

        $expected = [
            'id' => 123,
            'name' => 'Test DTO',
            'price' => 19.99,
            'isActive' => true,
            'nullableString' => null,
            'tags' => ['php', 'test', 'transformer'],
            'createdAt' => [
                'date' => '2025-05-27 18:30:37.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC',
            ],
            'updatedAt' => [
                'date' => '2025-05-28 19:31:35.000000',
                'timezone_type' => 3,
                'timezone' => 'UTC',
            ],
            'parent' => null,
            'child' => null,
            'address' => [
                'street' => 'Main St',
                'city' => 'New York',
            ],
            'products' => [
                [
                    'sku' => 'PROD-123',
                    'price' => 29.99,
                    'attributes' => ['color' => 'red'],
                ],
                [
                    'sku' => 'PROD-456',
                    'price' => 39.99,
                    'attributes' => ['size' => 'L'],
                ],
            ],
            'categories' => [
                1 => [
                    'id' => 0,
                    'name' => 'Root',
                    'parent' => [
                        'id' => 1,
                        'name' => 'Child',
                        'parent' => null,
                    ],
                ],
            ],
            'untypedProperty' => 'untyped value', // Accessed via reflection or getter
            'metadata' => [
                'key' => 'value',
            ],
            'stringableObject' => [],
            'jsonSerializableObject' => [
                'json' => 'data',
            ],
            'iterableProperty' => [1, 2, 3],
            'closureProperty' => [], // Typically excluded or shown as '[Closure]'
            'exception' => [],
        ];

        $this->assertEquals($expected, $result);
    }
}
