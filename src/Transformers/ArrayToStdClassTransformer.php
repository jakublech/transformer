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
use JakubLech\Transformer\Exception\TransformException;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use stdClass;

final class ArrayToStdClassTransformer implements TransformerInterface
{
    /**
     * @param array $input
     * @throws TransformException | UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): stdClass
    {
        AssertInputType::strict($input, $this);

        $stdClass = new stdClass();
        foreach ($input as $key => $value) {
            $stdClass->$key = (is_array($value))
                ? ($this)($input, $context)
                : $value;
        }

        return $stdClass;
    }

    public static function inputType(): string
    {
        return 'array';
    }

    public static function returnType(): string
    {
        return stdClass::class;
    }

    public static function priority(): int
    {
        return -1000;
    }
}
