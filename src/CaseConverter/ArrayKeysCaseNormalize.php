<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\CaseConverter;

final class ArrayKeysCaseNormalize
{
    /**
     * Detect and recursively normalize array keys to a consistent format.
     */
    public static function normalize(
        array $array,
        CaseFormat $targetFormat,
    ): array {
        $result = [];

        foreach ($array as $key => $value) {
            $detectedFormat = CaseFormat::detect($key);
            $newKey = CaseConverter::convert($key, $detectedFormat, $targetFormat);

            $newValue = is_array($value)
                ? self::normalize($value, $targetFormat)
                : $value;

            $result[$newKey] = $newValue;
        }

        return $result;
    }
}
