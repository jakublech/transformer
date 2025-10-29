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
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\TransformHandler;
use JakubLech\Transformer\Transformers\TransformerInterface;
use JsonSerializable;

final class JsonSerializableToArrayTransformer implements TransformerInterface
{
    public function __construct(private TransformHandler $transform){}

    public static function inputType(): string { return JsonSerializable::class;}

    public static function returnType(): string { return 'array';}

    /**
     * @param JsonSerializable $input
     *
     * @throws UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        return $this->transform->transform($input->jsonSerialize(), 'array', $context);
    }
}
