<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\TypesTransformer\Throwable;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformer;
use JakubLech\Transformer\TypesTransformer\Array\ArrayToJsonTypesTransformer;
use JakubLech\Transformer\TypesTransformer\TypesTransformerInterface;
use Throwable;

final readonly class ThrowableToJsonTypesTransformer implements TypesTransformerInterface
{
    public function __construct(private Transformer $transformer)
    {
    }

    /**
     * @param Throwable $input
     *
     * @throws UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): string
    {
        AssertInputType::strict($input, $this);

        return (new ArrayToJsonTypesTransformer())(
            ($this->transformer)(
                $input,
                'array',
                $context
            ),
            $context
        );
    }

    public static function inputType(): string
    {
        return Throwable::class;
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
