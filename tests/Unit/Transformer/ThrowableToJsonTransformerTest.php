<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Tests\Unit\Transformer;

use Exception;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformers\Throwable\ThrowableToJsonTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
final class ThrowableToJsonTransformerTest extends TestCase
{
    private ThrowableToJsonTransformer $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new ThrowableToJsonTransformer();
    }

    public function testInvokeWithThrowable(): void
    {
        $throwable = new Exception('Test exception', 123);
        $expected = json_encode([
            'error' => 'Test exception',
            'code' => 123,
        ]);

        $result = ($this->sut)($throwable);

        $this->assertIsString($result);
        $this->assertJsonStringEqualsJsonString($expected, $result);
    }

    public function testInvokeWithNonThrowableThrowsException(): void
    {
        $this->expectException(UnsupportedInputTypeException::class);

        $input = 'not-a-throwable';
        ($this->sut)($input);
    }

    public function testPriority(): void
    {
        $this->assertEquals(-1000, ThrowableToJsonTransformer::priority());
    }

    public function testInputType(): void
    {
        $this->assertEquals('Throwable', ThrowableToJsonTransformer::inputType());
    }

    public function testReturnType(): void
    {
        $this->assertEquals('json', ThrowableToJsonTransformer::returnType());
    }
}
