<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer;

use JakubLech\Transformer\Exception\UnsupportedTransformationException;
use JakubLech\Transformer\TypesTransformer\TypesTransformerInterface;
use JakubLech\Transformer\TypesTransformer\TypesTransformersCollectionInterface;

final readonly class Transformer
{
    public function __construct(private TypesTransformersCollectionInterface $transformers)
    {
    }

    /**
     * @throws UnsupportedTransformationException
     */
    public function __invoke(mixed $input, string $outputType, array $context = []): mixed
    {
        return $this->transformers->get($input, $outputType)($input, $context);
    }

    public function convert(mixed $input, string $outputType, array $context = []): mixed
    {
        return $this->__invoke($input, $outputType, $context);
    }

    public function addTransformer(TypesTransformerInterface $transformer): void
    {
        $this->transformers->add($transformer);
    }
}
