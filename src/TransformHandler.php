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
use JakubLech\Transformer\Transformers\TransformersCollectionInterface;

final readonly class TransformHandler
{
    public function __construct(private TransformersCollectionInterface $transformers){}

    /**
     * @throws UnsupportedTransformationException
     */
    public function __invoke(TransformCommand $command): mixed
    {
        return $this->transform($command->input, $command->outputType, $command->context);
    }

    public function transform(mixed $input, string $outputType, array $context = []): mixed
    {
        return $this->transformers->get($input, $outputType)($input, $context);
    }
}
