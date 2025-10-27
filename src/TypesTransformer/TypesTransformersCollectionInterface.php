<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\TypesTransformer;

use JakubLech\Transformer\Exception\UnsupportedTransformationException;

interface TypesTransformersCollectionInterface
{
    public function __construct(iterable $transformers = []);

    public function add(TypesTransformerInterface $transformer): void;

    /**
     * @throws UnsupportedTransformationException
     */
    public function get(mixed $input, string $outputType): TypesTransformerInterface;

    public function find(mixed $input, string $outputType): ?TypesTransformerInterface;
}
