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
use JakubLech\Transformer\TransformHandler as Transform;
use JakubLech\Transformer\Transformers\Array\ArrayToJsonTransformer;
use JakubLech\Transformer\TransformHandlerFactory;
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
        $this->sut = TransformHandlerFactory::defaultPhpNativeTypesTransformHandler();
    }

    public function testInvokeWithSupportedTransformation(): void
    {
        $input = ['key' => 'value'];
        $expected = '{"key":"value"}';

        $result = ($this->sut)->transform($input, 'json');

        $this->assertEquals($expected, $result);
    }

    public function testInvokeWithUnsupportedTransformation(): void
    {
        $this->expectException(UnsupportedTransformationException::class);

        $input = 'unsupported-input';
        $outputType = 'json';

        ($this->sut)->transform($input, $outputType);
    }
}
