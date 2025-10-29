<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers;

use JakubLech\Transformer\Exception\UnsupportedTransformationException;

interface TransformersCollectionInterface
{
    public function add(TransformerInterface $transformer, bool $overwrite = false): void;

    /**
     * @throws UnsupportedTransformationException
     */
    public function get(mixed $input, string $outputType): TransformerInterface;

    public function find(mixed $input, string $outputType): ?TransformerInterface;
}
