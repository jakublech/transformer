<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers\GenericObject;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transform;
use JakubLech\Transformer\Transformers\TransformerInterface;

final class ObjectToJsonTransformer implements TransformerInterface
{
    public function __construct(private Transform $transform)
    {
    }

    /**
     * @param object $input
     *
     * @throws UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): string
    {
        AssertInputType::strict($input, $this);

        return ($this->transform)(
            ($this->transform)($input, 'array', $context),
            'json',
            $context
        );
    }

    public static function inputType(): string
    {
        return 'object';
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
