<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Tests\Unit\Transformer\Array;

use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\TypesTransformer\Array\ArrayToJsonTypesTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
final class ArrayToJsonTransformerTest extends TestCase
{
    /** @var ArrayToJsonTypesTransformer */
    private $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new ArrayToJsonTypesTransformer();
    }

    public function testInvoke(): void
    {
        $input = ['key' => 'value', 'another_key' => 123];
        $expected = '{"key":"value","another_key":123}';

        $result = ($this->sut)($input);

        $this->assertIsString($result);
        $this->assertEquals($expected, $result);
    }

    public function testInvokeWithEmptyArray(): void
    {
        $input = [];
        $expected = '[]';

        $result = ($this->sut)($input);

        $this->assertIsString($result);
        $this->assertEquals($expected, $result);
    }

    public function testInvokeWithNoArrayWillThrowException(): void
    {
        $input = 'not-an-array';
        $this->expectException(UnsupportedInputTypeException::class);

        ($this->sut)($input);
    }

    public function testPriority(): void
    {
        $this->assertEquals(-1000, ArrayToJsonTypesTransformer::priority());
    }

    public function testInputType(): void
    {
        $this->assertEquals('array', ArrayToJsonTypesTransformer::inputType());
    }

    public function testReturnType(): void
    {
        $this->assertEquals('json', ArrayToJsonTypesTransformer::returnType());
    }
}
