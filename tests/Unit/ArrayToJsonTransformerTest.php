<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Tests\Unit;

use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformers\ArrayToJsonTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
final class ArrayToJsonTransformerTest extends TestCase
{
    /** @var ArrayToJsonTransformer */
    private $sut;

    /** @test */
    public function testInvoke(): void
    {
        $input = ['key' => 'value', 'another_key' => 123];
        $expected = '{"key":"value","another_key":123}';

        $result = ($this->sut)($input);

        $this->assertIsString($result);
        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function testInvokeWithEmptyArray(): void
    {
        $input = [];
        $expected = '[]';

        $result = ($this->sut)($input);

        $this->assertIsString($result);
        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function testInvokeWithNoArrayWillThrowException(): void
    {
        $input = 'not-an-array';
        $this->expectException(UnsupportedInputTypeException::class);

        ($this->sut)($input);
    }

    /** @test */
    public function testPriority(): void
    {
        $this->assertEquals(-1000, ArrayToJsonTransformer::priority());
    }

    /** @test */
    public function testInputType(): void
    {
        $this->assertEquals('array', ArrayToJsonTransformer::inputType());
    }

    /** @test */
    public function testReturnType(): void
    {
        $this->assertEquals('json', ArrayToJsonTransformer::returnType());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new ArrayToJsonTransformer();
    }
}
