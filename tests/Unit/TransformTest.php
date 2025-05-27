<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Tests\Unit;

use JakubLech\Transformer\Exception\UnsupportedTransformationException;
use JakubLech\Transformer\Transform;
use JakubLech\Transformer\Transformers\ArrayIteratorToArrayTransformer;
use JakubLech\Transformer\Transformers\ArrayToArrayTransformer;
use JakubLech\Transformer\Transformers\ArrayToJsonTransformer;
use JakubLech\Transformer\Transformers\ArrayToStdClassTransformer;
use JakubLech\Transformer\Transformers\CallableToArrayTransformer;
use JakubLech\Transformer\Transformers\ClosureToArrayTransformer;
use JakubLech\Transformer\Transformers\DateTimeInterfaceToArrayTransformer;
use JakubLech\Transformer\Transformers\IterableToArrayTransformer;
use JakubLech\Transformer\Transformers\IteratorAggregateToArrayTransformer;
use JakubLech\Transformer\Transformers\JsonSerializableToArray;
use JakubLech\Transformer\Transformers\ObjectToArrayTransformer;
use JakubLech\Transformer\Transformers\ObjectToJsonTransformer;
use JakubLech\Transformer\Transformers\StdClassToArray;
use JakubLech\Transformer\Transformers\StringableToArrayTransformer;
use JakubLech\Transformer\Transformers\ThrowableToArrayTransformer;
use JakubLech\Transformer\Transformers\ThrowableToJsonTransformer;
use PHPUnit\Framework\TestCase;

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

    public function testFindTransformerReturnsTransformerForSupportedTransformation(): void
    {
        $input = ['key' => 'value'];
        $outputType = 'json';

        $transformer = $this->sut->find($input, $outputType);

        $this->assertSame($this->transformer, $transformer);
    }

    public function testFindTransformerReturnsNullForUnsupportedTransformation(): void
    {
        $input = 'unsupported-input';
        $outputType = 'json';

        $transformer = $this->sut->find($input, $outputType);

        $this->assertNull($transformer);
    }
}
