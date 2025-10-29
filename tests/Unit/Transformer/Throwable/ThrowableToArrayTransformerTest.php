<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Tests\Unit\Transformer\Throwable;

use Exception;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformers\Throwable\ThrowableToArrayTransformer;
use JakubLech\Transformer\TransformHandlerFactory;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * @coversNothing
 */
final class ThrowableToArrayTransformerTest extends TestCase
{
    private ThrowableToArrayTransformer $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $transform = TransformHandlerFactory::defaultPhpNativeTypesTransformHandler();
        $this->sut = new ThrowableToArrayTransformer($transform);
    }

    public function testInvokeWithThrowable(): void
    {
        $throwable = new Exception('Test exception', 123);
        $expected = [
            'error' => 'Test exception',
            'code' => 123,
        ];

        $result = ($this->sut)($throwable);

        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    public function testInvokeWithNonThrowableThrowsException(): void
    {
        $this->expectException(UnsupportedInputTypeException::class);

        $input = 'not-a-throwable';
        ($this->sut)($input);
    }

    public function testInputType(): void
    {
        $this->assertEquals(Throwable::class, ThrowableToArrayTransformer::inputType());
    }

    public function testReturnType(): void
    {
        $this->assertEquals('array', ThrowableToArrayTransformer::returnType());
    }
}
