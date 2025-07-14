<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers\Json;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\TransformException;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transform;
use JakubLech\Transformer\Transformers\TransformerInterface;

final class JsonToObject implements TransformerInterface
{
    public function __construct(private Transform $transform)
    {
    }

    /**
     * @param string $input
     *
     * @throws UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($this->transform, $input);

        $flags = $context['_flags'] ?? 0;
        $depth = $context['_depth'] ?? 512;

        $result = json_decode($input, null, $depth, $flags);

        if (JSON_ERROR_NONE !== json_last_error() || false === $result) {
            throw new TransformException('Can not transform json string to object. ' . json_last_error_msg());
        }

        return ($this->transform)($result, 'object', $context);
    }

    public static function inputType(): string
    {
        return 'string';
    }

    public static function returnType(): string
    {
        return 'object';
    }

    public static function priority(): int
    {
        return -1000;
    }
}
