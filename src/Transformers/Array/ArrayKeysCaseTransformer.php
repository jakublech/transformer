<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers\Array;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\CaseConverter\ArrayKeysCaseConvert;
use JakubLech\Transformer\CaseConverter\CaseFormat;
use JakubLech\Transformer\Exception\TransformException;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformers\TransformerInterface;

final class ArrayKeysCaseTransformer implements TransformerInterface
{
    public static function inputType(): string { return 'array';}

    public static function returnType(): string { return 'arrayKeysCaseFormat';}
    /**
     * Transforms nested arrays.
     *
     * @param array $input
     *
     * @throws TransformException|UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        $targetCaseFormat = CaseFormat::from($context['targetArrayKeysCaseFormat']);
        $sourceCaseFormat = CaseFormat::tryFrom($context['sourceArrayKeysCaseFormat'] ?? '');

        return ArrayKeysCaseConvert::convert($input, $targetCaseFormat, $sourceCaseFormat);
    }
}
