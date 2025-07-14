<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Tests\Unit\CaseConverter;

use JakubLech\Transformer\CaseConverter\ArrayKeysCaseNormalize;
use JakubLech\Transformer\CaseConverter\CaseFormat;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
final class ArrayKeysCaseNormalizeTest extends TestCase
{
    public function testNormalizeToKebab(): void
    {
        $mixedData = [
            'UserName' => 'John',
            'account-details' => [
                'IS_ACTIVE' => true,
                'lastLogin' => '2023-05-20',
            ],
        ];

        $normalized = ArrayKeysCaseNormalize::normalize(
            $mixedData,
            CaseFormat::KEBAB,
        );

        $expected = [
            'user-name' => 'John',
            'account-details' => [
                'is-active' => true,
                'last-login' => '2023-05-20',
            ],
        ];

        $this->assertEquals($expected, $normalized);
    }

    public function testNormalizeToSnake(): void
    {
        $mixedData = [
            'UserName' => 'John',
            'account-details' => [
                'IS_ACTIVE' => true,
                'lastLogin' => '2023-05-20',
            ],
        ];

        $normalized = ArrayKeysCaseNormalize::normalize(
            $mixedData,
            CaseFormat::SNAKE,
        );

        $expected = [
            'user_name' => 'John',
            'account_details' => [
                'is_active' => true,
                'last_login' => '2023-05-20',
            ],
        ];

        $this->assertEquals($expected, $normalized);
    }

    public function testNormalizeToCamel(): void
    {
        $mixedData = [
            'UserName' => 'John',
            'account-details' => [
                'IS_ACTIVE' => true,
                'lastLogin' => '2023-05-20',
            ],
        ];

        $normalized = ArrayKeysCaseNormalize::normalize(
            $mixedData,
            CaseFormat::CAMEL,
        );

        $expected = [
            'userName' => 'John',
            'accountDetails' => [
                'isActive' => true,
                'lastLogin' => '2023-05-20',
            ],
        ];

        $this->assertEquals($expected, $normalized);
    }
}
