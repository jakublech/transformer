<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer;

use JakubLech\Transformer\TypesTransformer\Array\ArrayIteratorToArrayTypesTransformer;
use JakubLech\Transformer\TypesTransformer\Array\ArrayToArrayTypesTransformer;
use JakubLech\Transformer\TypesTransformer\Array\ArrayToJsonTypesTransformer;
use JakubLech\Transformer\TypesTransformer\Array\ArrayToStdClassTypesTransformer;
use JakubLech\Transformer\TypesTransformer\Array\IterableToArrayTypesTransformer;
use JakubLech\Transformer\TypesTransformer\Array\IteratorAggregateToArrayTypesTransformer;
use JakubLech\Transformer\TypesTransformer\Callable\CallableToArrayTypesTransformer;
use JakubLech\Transformer\TypesTransformer\Callable\ClosureToArrayTypesTransformer;
use JakubLech\Transformer\TypesTransformer\DateTime\DateTimeInterfaceToArrayTypesTransformer;
use JakubLech\Transformer\TypesTransformer\GenericObject\ObjectToArrayCompositeTypesTransformer;
use JakubLech\Transformer\TypesTransformer\GenericObject\ObjectToJsonTypesTransformer;
use JakubLech\Transformer\TypesTransformer\GenericObject\StdClassToArray;
use JakubLech\Transformer\TypesTransformer\Json\JsonSerializableToArray;
use JakubLech\Transformer\TypesTransformer\Json\JsonToArray;
use JakubLech\Transformer\TypesTransformer\Json\JsonToObject;
use JakubLech\Transformer\TypesTransformer\Stringable\StringableToArrayTypesTransformer;
use JakubLech\Transformer\TypesTransformer\Stringable\StringableToStringTypesTransformer;
use JakubLech\Transformer\TypesTransformer\Throwable\ThrowableToArrayTypesTransformer;
use JakubLech\Transformer\TypesTransformer\Throwable\ThrowableToJsonTypesTransformer;
use JakubLech\Transformer\TypesTransformer\TypesTransformersCollection;

final readonly class TransformerFactory
{
    public static function defaultPhpNativeTypesTransformer(bool $debug = false): Transformer
    {
        $collection = new TypesTransformersCollection();
        $transformer = new Transformer($collection);

        $transformer->addTransformer(new ArrayIteratorToArrayTypesTransformer($transformer));
        $transformer->addTransformer(new ArrayToArrayTypesTransformer($transformer));
        $transformer->addTransformer(new ArrayToJsonTypesTransformer());
        $transformer->addTransformer(new ArrayToStdClassTypesTransformer());
        $transformer->addTransformer(new IterableToArrayTypesTransformer($transformer));
        $transformer->addTransformer(new IteratorAggregateToArrayTypesTransformer($transformer));

        $transformer->addTransformer(new CallableToArrayTypesTransformer());
        $transformer->addTransformer(new ClosureToArrayTypesTransformer());

        $transformer->addTransformer(new DateTimeInterfaceToArrayTypesTransformer());

        $transformer->addTransformer(new ObjectToArrayCompositeTypesTransformer($transformer));
        $transformer->addTransformer(new ObjectToJsonTypesTransformer($transformer));
        $transformer->addTransformer(new StdClassToArray($transformer));

        $transformer->addTransformer(new JsonSerializableToArray($transformer));
        $transformer->addTransformer(new JsonToArray($transformer));
        $transformer->addTransformer(new JsonToObject($transformer));

        $transformer->addTransformer(new StringableToArrayTypesTransformer());
        $transformer->addTransformer(new StringableToStringTypesTransformer());

        $transformer->addTransformer(new ThrowableToArrayTypesTransformer($transformer, $debug));
        $transformer->addTransformer(new ThrowableToJsonTypesTransformer($transformer));

        return $transformer;
    }
}
