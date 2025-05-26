<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers;


use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;

class JsonSerializableToArray implements TransformerInterface
{
    /**
     * @param \JsonSerializable $input
     * @throws UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        $array = $input->jsonSerialize();
        return is_array($array) ? $array : ['value' => $array];
    }

    public static function inputType(): string
    {
        return \JsonSerializable::class;
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
