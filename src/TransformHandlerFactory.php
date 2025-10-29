<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer;

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
use JakubLech\Transformer\Transformers\Json\JsonToArray;
use JakubLech\Transformer\Transformers\Json\JsonToObject;
use JakubLech\Transformer\Transformers\Stringable\StringableToArrayTransformer;
use JakubLech\Transformer\Transformers\Stringable\StringableToStringTransformer;
use JakubLech\Transformer\Transformers\Throwable\ThrowableToArrayTransformer;
use JakubLech\Transformer\Transformers\Throwable\ThrowableToJsonTransformer;
use JakubLech\Transformer\Transformers\TransformersCollection;

final readonly class TransformHandlerFactory
{
    public static function defaultPhpNativeTypesTransformHandler(bool $debug = false): TransformHandler
    {
        $collection = new TransformersCollection();
        $handler = new TransformHandler($collection);

        $collection->add(new ArrayIteratorToArrayTransformer($handler));
        $collection->add(new ArrayToArrayTransformer($handler));
        $collection->add(new ArrayToJsonTransformer());
        $collection->add(new ArrayToStdClassTransformer());
        $collection->add(new IterableToArrayTransformer($handler));
        $collection->add(new IteratorAggregateToArrayTransformer($handler));

        $collection->add(new CallableToArrayTransformer());
        $collection->add(new ClosureToArrayTransformer());

        $collection->add(new DateTimeInterfaceToArrayTransformer());

        $collection->add(new ObjectToArrayCompositeTransformer($handler));
        $collection->add(new ObjectToJsonTransformer($handler));
        $collection->add(new StdClassToArray($handler));

        $collection->add(new JsonSerializableToArray($handler));
        $collection->add(new JsonToArray($handler));
        $collection->add(new JsonToObject($handler));

        $collection->add(new StringableToArrayTransformer());
        $collection->add(new StringableToStringTransformer());

        $collection->add(new ThrowableToArrayTransformer($handler, $debug));
        $collection->add(new ThrowableToJsonTransformer($handler));

        return $handler;
    }
}
