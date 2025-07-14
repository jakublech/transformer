<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\CaseConverter;

final class CaseConverter
{
    /**
     * Convert string between different case formats.
     */
    public static function convert(string $input, CaseFormat $fromFormat, CaseFormat $toFormat): string
    {
        $words = self::parseToWords($input, $fromFormat);

        return self::wordsToFormat($words, $toFormat);
    }

    /**
     * Auto-detect an input format and convert to the target format.
     */
    public static function autoConvert(string $input, CaseFormat $toFormat): string
    {
        return self::convert($input, CaseFormat::detect($input), $toFormat);
    }

    private static function parseToWords(string $input, CaseFormat $format): array
    {
        return match ($format) {
            CaseFormat::UPPER_SNAKE, CaseFormat::SNAKE => explode('_', strtolower($input)),
            CaseFormat::CAMEL => preg_split('/(?=[A-Z])/', lcfirst($input)),
            CaseFormat::KEBAB => explode('-', strtolower($input)),
            CaseFormat::PASCAL => preg_split('/(?=[A-Z])/', $input),
        };
    }

    private static function wordsToFormat(array $words, CaseFormat $format): string
    {
        $filtered = array_filter($words); // Remove empty elements

        return match ($format) {
            CaseFormat::SNAKE => strtolower(implode('_', $filtered)),
            CaseFormat::UPPER_SNAKE => strtoupper(implode('_', $filtered)),
            CaseFormat::CAMEL => lcfirst(implode('', array_map('ucfirst', $filtered))),
            CaseFormat::KEBAB => strtolower(implode('-', $filtered)),
            CaseFormat::PASCAL => implode('', array_map('ucfirst', $filtered)),
        };
    }
}
