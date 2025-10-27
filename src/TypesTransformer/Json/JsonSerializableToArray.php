<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\TypesTransformer\Json;

use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformer;
use JakubLech\Transformer\TypesTransformer\TypesTransformerInterface;
use JsonSerializable;

final class JsonSerializableToArray implements TypesTransformerInterface
{
    public function __construct(private Transformer $transform)
    {
    }

    /**
     * @param JsonSerializable $input
     *
     * @throws UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        return ($this->transform)($input->jsonSerialize(), 'array', $context);
    }

    public static function inputType(): string
    {
        return JsonSerializable::class;
    }

    public static function returnType(): string
    {
        return 'array';
    }

    public static function priority(): int
    {
        return -1000;
    }
}
