<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\CaseConverter;

/**
 * Enumeration of supported case conversion formats.
 */
enum CaseFormat: string
{
    /**
     * camelCase format (e.g., "userName").
     */
    case CAMEL = 'camel';

    /**
     * PascalCase format (e.g., "UserName").
     */
    case PASCAL = 'pascal';

    /**
     * snake_case format (e.g., "user_name").
     */
    case SNAKE = 'snake';

    /**
     * kebab-case format (e.g., "user-name").
     */
    case KEBAB = 'kebab';

    /**
     * UPPER_SNAKE format (e.g., "USER_NAME").
     */
    case UPPER_SNAKE = 'upper_snake';

    /**
     * Detects the format of a given string.
     */
    public static function detect(string $input): self
    {
        return match (true) {
            str_contains($input, '-') => self::KEBAB,
            str_contains($input, '_') => ctype_upper(str_replace('_', '', $input))
                ? self::UPPER_SNAKE
                : self::SNAKE,
            ctype_lower($input[0] ?? '') => self::CAMEL,
            default => self::PASCAL,
        };
    }
}
