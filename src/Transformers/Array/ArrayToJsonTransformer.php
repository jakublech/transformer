<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers\Array;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\TransformException;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformers\TransformerInterface;

final class ArrayToJsonTransformer implements TransformerInterface
{
    /**
     * @throws TransformException | UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): string
    {
        AssertInputType::strict($input, $this);

        $flags = $context['_flags'] ?? 0;
        $depth = $context['_depth'] ?? 512;

        $result = json_encode($input, $flags, $depth);
        if (JSON_ERROR_NONE !== json_last_error() || false === $result) {
            throw new TransformException('Can not transform array to json. ' . json_last_error_msg());
        }

        return $result;
    }

    public static function inputType(): string
    {
        return 'array';
    }

    public static function returnType(): string
    {
        return 'json';
    }

    public static function priority(): int
    {
        return -1000;
    }
}
