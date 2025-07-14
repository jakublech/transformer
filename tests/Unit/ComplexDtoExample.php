<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Tests\Unit;

use ArrayIterator;
use Closure;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use JsonSerializable;
use Stringable;
use stdClass;

final class ComplexDtoExample
{
    // Primitive types
    public int $id;
    public string $name;
    public float $price;
    public bool $isActive;
    public ?string $nullableString;

    // Compound types
    public array $tags;
    public DateTimeInterface $createdAt;
    public DateTimeImmutable $updatedAt;

    // Nested DTOs
    public ?self $parent;
    public ?self $child;
    public ?AddressDto $address;

    // Collections
    /** @var ProductDto[] */
    public array $products = [];

    /** @var array<int, CategoryDto> */
    public array $categories;

    // Special cases
    public $untypedProperty;
    public stdClass $metadata;
    public Stringable $stringableObject;
    public JsonSerializable $jsonSerializableObject;
    public iterable $iterableProperty;
    public Closure $closureProperty;
    public Exception $exception;
    private string $privateProperty;
    private array $protectedArray = [];

    public function __construct(
        int $id,
        string $name,
        float $price,
        bool $isActive,
        ?string $nullableString,
        array $tags,
        DateTimeInterface $createdAt,
        DateTimeImmutable $updatedAt,
        ?self $parent,
        ?self $child,
        ?AddressDto $address,
        array $products,
        array $categories,
        $untypedProperty,
        string $privateProperty,
        array $protectedArray,
        stdClass $metadata,
        Stringable $stringableObject,
        JsonSerializable $jsonSerializableObject,
        iterable $iterableProperty,
        Closure $closureProperty,
        Exception $exception,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->isActive = $isActive;
        $this->nullableString = $nullableString;
        $this->tags = $tags;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->parent = $parent;
        $this->child = $child;
        $this->address = $address;
        $this->products = $products;
        $this->categories = $categories;
        $this->untypedProperty = $untypedProperty;
        $this->privateProperty = $privateProperty;
        $this->protectedArray = $protectedArray;
        $this->metadata = $metadata;
        $this->stringableObject = $stringableObject;
        $this->jsonSerializableObject = $jsonSerializableObject;
        $this->iterableProperty = $iterableProperty;
        $this->closureProperty = $closureProperty;
        $this->exception = $exception;
    }

    public static function createFullyPopulated(): self
    {
        return new self(
            123,
            'Test DTO',
            19.99,
            true,
            null,
            ['php', 'test', 'transformer'],
            new DateTimeImmutable('2025-05-27 18:30:37'),
            new DateTimeImmutable('2025-05-28 19:31:35'),
            null,
            null,
            new AddressDto(),
            [
                new ProductDto('PROD-123', 29.99, ['color' => 'red']),
                new ProductDto('PROD-456', 39.99, ['size' => 'L']),
            ],
            [1 => new CategoryDto(
                0,
                'Root',
                new CategoryDto(1, 'Child', null),
            )],
            'untyped value',
            'private value',
            ['protected' => 'data'],
            (object)['key' => 'value'],
            new class implements Stringable {
                public function __toString(): string
                {
                    return 'stringable';
                }
            },
            new class implements JsonSerializable {
                public function jsonSerialize(): array
                {
                    return ['json' => 'data'];
                }
            },
            new ArrayIterator([1, 2, 3]),
            fn() => 'closure result',
            new Exception('some exception', 404),
        );
    }

    public function setPrivateProperty(string $value): void
    {
        $this->privateProperty = $value;
    }

    public function getPrivateProperty(): string
    {
        return $this->privateProperty;
    }
}

// Supporting DTOs
final class AddressDto
{
    public string $street = 'Main St';
    public string $city = 'New York';
    private string $zip = '10001';

    public function getZip(): string
    {
        return $this->zip;
    }
}

final class ProductDto
{
    public string $sku = 'PROD-123';
    public float $price = 29.99;
    public array $attributes = ['color' => 'red'];

    public function __construct(string $sku, float $price, array $attributes)
    {
        $this->price = $price;
        $this->sku = $sku;
        $this->attributes = $attributes;
    }
}

final class CategoryDto
{
    public int $id = 1;
    public string $name = 'Test Category';
    public ?self $parent = null;

    public function __construct(int $id, string $name, ?CategoryDto $parent)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parent = $parent;
    }
}
