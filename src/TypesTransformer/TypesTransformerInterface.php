<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\TypesTransformer;

use JakubLech\Transformer\Exception\TransformException;

interface TypesTransformerInterface
{
    /**
     * Transforms the input to the target type.
     *
     * @return mixed/**
     *
     * @throws TransformException
     */
    public function __invoke(mixed $input, array $context = []): mixed;

    /**
     * Input type which transformer receives.
     */
    public static function inputType(): string;

    /**
     * Output type which transformer returns.
     */
    public static function returnType(): string;

    /**
     * Transformer with higher priority overrides lower priorities (if input type and return type are the same).
     */
    public static function priority(): int;
}
