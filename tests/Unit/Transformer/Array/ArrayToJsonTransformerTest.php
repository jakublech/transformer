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
use JakubLech\Transformer\Transformers\Array\ArrayToJsonTransformer;
use PHPUnit\Framework\TestCase;

class ArrayToJsonTransformerTest extends TestCase
{
    /** @var ArrayToJsonTransformer */
    private $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new ArrayToJsonTransformer();
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
        $this->assertEquals(-1000, ArrayToJsonTransformer::priority());
    }

    public function testInputType(): void
    {
        $this->assertEquals('array', ArrayToJsonTransformer::inputType());
    }

    public function testReturnType(): void
    {
        $this->assertEquals('json', ArrayToJsonTransformer::returnType());
    }
}
