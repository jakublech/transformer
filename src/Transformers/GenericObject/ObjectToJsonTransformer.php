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
use JakubLech\Transformer\TransformHandler;
use JakubLech\Transformer\Transformers\TransformerInterface;

final class ObjectToJsonTransformer implements TransformerInterface
{
    public function __construct(private TransformHandler $transform){}

    public static function inputType(): string { return 'object';}

    public static function returnType(): string { return 'json';}

    /**
     * @param object $input
     *
     * @throws UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): string
    {
        AssertInputType::strict($input, $this);

        return $this->transform->transform(
            $this->transform->transform($input, 'array', $context),
            'json',
            $context
        );
    }
}
