<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use JakubLech\Transformer\Exception\UnsupportedTransformationException;
use JakubLech\Transformer\Transform;
use PHPUnit\Framework\TestCase;
use JakubLech\Transformer\Transformers\ArrayToJsonTransformer;

/**
 * @coversNothing
 */
final class TransformTest extends TestCase
{
    private Transform $sut;
    private ArrayToJsonTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transformer = new ArrayToJsonTransformer();
        $this->sut = new Transform([
            $this->transformer,
        ]);
    }

    public function testInvokeWithSupportedTransformation(): void
    {
        $input = ['key' => 'value'];
        $expected = '{"key":"value"}';

        $result = ($this->sut)($input, 'json');

        $this->assertEquals($expected, $result);
    }

    public function testInvokeWithUnsupportedTransformation(): void
    {
        $this->expectException(UnsupportedTransformationException::class);

        $input = 'unsupported-input';
        $outputType = 'json';

        ($this->sut)($input, $outputType);
    }

    public function testSupportsReturnsTrueForSupportedTransformation(): void
    {
        $input = ['key' => 'value'];
        $outputType = 'json';

        $this->assertTrue($this->sut->supports($input, $outputType));
    }

    public function testSupportsReturnsFalseForUnsupportedTransformation(): void
    {
        $input = 'unsupported-input';
        $outputType = 'json';

        $this->assertFalse($this->sut->supports($input, $outputType));
    }

    public function testFindTransformerReturnsTransformerForSupportedTransformation(): void
    {
        $input = ['key' => 'value'];
        $outputType = 'json';

        $transformer = $this->sut->findTransformer($input, $outputType);

        $this->assertSame($this->transformer, $transformer);
    }

    public function testFindTransformerReturnsNullForUnsupportedTransformation(): void
    {
        $input = 'unsupported-input';
        $outputType = 'json';

        $transformer = $this->sut->findTransformer($input, $outputType);

        $this->assertNull($transformer);
    }
}
