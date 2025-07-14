<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\CaseConverter;

final class MultibyteCaseConverter
{
    /**
     * Convert string between different case formats
     */
    public static function convert(string $input, CaseFormat $fromFormat, CaseFormat $toFormat): string
    {
        $words = self::parseToWords($input, $fromFormat);

        return self::wordsToFormat($words, $toFormat);
    }

    /**
     * Auto-detect an input format and convert to the target format
     */
    public static function autoConvert(string $input, CaseFormat $toFormat): string
    {
        return self::convert($input, CaseFormat::detect($input), $toFormat);
    }

    private static function parseToWords(string $input, CaseFormat $format): array
    {
        return match ($format) {
            CaseFormat::CAMEL => preg_split('/(?=\p{Lu})/u', lcfirst($input)),
            CaseFormat::PASCAL => preg_split('/(?=\p{Lu})/u', $input),
            CaseFormat::SNAKE, CaseFormat::UPPER_SNAKE => explode('_', mb_strtolower($input, 'UTF-8')),
            CaseFormat::KEBAB => explode('-', mb_strtolower($input, 'UTF-8')),
        };
    }

    private static function wordsToFormat(array $words, CaseFormat $format): string
    {
        $filtered = array_filter($words);
        $lowerWords = array_map(fn($w) => mb_strtolower($w, 'UTF-8'), $filtered);

        return match ($format) {
            CaseFormat::CAMEL => lcfirst(implode('', array_map(
                fn($w) => mb_convert_case($w, MB_CASE_TITLE, 'UTF-8'),
                $lowerWords
            ))),
            CaseFormat::PASCAL => implode('', array_map(
                fn($w) => mb_convert_case($w, MB_CASE_TITLE, 'UTF-8'),
                $lowerWords
            )),
            CaseFormat::SNAKE => implode('_', $lowerWords),
            CaseFormat::KEBAB => implode('-', $lowerWords),
            CaseFormat::UPPER_SNAKE => mb_strtoupper(implode('_', $lowerWords), 'UTF-8'),
        };
    }
}
