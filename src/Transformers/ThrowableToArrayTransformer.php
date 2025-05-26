<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers;

use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use Throwable;

final class ThrowableToArrayTransformer implements TransformerInterface
{
    /**
     * @param Throwable $input
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        if (!is_a($input, self::inputType())) {
            throw new UnsupportedInputTypeException();
        }

        return [
            'error' => $input->getMessage(),
            'code' => $input->getCode(),
        ];
    }

    public static function inputType(): string
    {
        return Throwable::class;
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
