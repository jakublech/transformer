<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\TypesTransformer\GenericObject;

use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformer;
use JakubLech\Transformer\TypesTransformer\TypesTransformerInterface;
use stdClass;

final class StdClassToArray implements TypesTransformerInterface
{
    public function __construct(private Transformer $transform)
    {
    }

    /**
     * @param stdClass $input
     *
     * @throws UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        return ($this->transform)((array) $input, 'array', $context);
    }

    public static function inputType(): string
    {
        return stdClass::class;
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
