<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\CaseConverter;

final class ArrayKeysCaseConvert
{
    /**
     * Recursively convert array keys to a specified case format.
     */
    public static function convert(
        array $array,
        CaseFormat $targetFormat,
        ?CaseFormat $sourceFormat = null,
    ): array {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $sourceFormat
                ? CaseConverter::convert($key, $sourceFormat, $targetFormat)
                : CaseConverter::autoConvert($key, $targetFormat);

            $newValue = is_array($value)
                ? self::convert($value, $targetFormat, $sourceFormat)
                : $value;

            $result[$newKey] = $newValue;
        }

        return $result;
    }
}
