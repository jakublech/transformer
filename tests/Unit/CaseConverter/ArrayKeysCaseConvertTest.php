<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Tests\Unit\CaseConverter;

use JakubLech\Transformer\CaseConverter\ArrayKeysCaseConvert;
use JakubLech\Transformer\CaseConverter\CaseFormat;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
final class ArrayKeysCaseConvertTest extends TestCase
{
    public function testConvert(): void
    {
        $data = [
            'user_name' => 'John',
            'account_details' => [
                'is_active' => true,
                'last_login' => '2023-05-20',
            ],
        ];

        $converted = ArrayKeysCaseConvert::convert(
            $data,
            CaseFormat::CAMEL,
            CaseFormat::SNAKE,
        );

        $expected = [
            'userName' => 'John',
            'accountDetails' => [
                'isActive' => true,
                'lastLogin' => '2023-05-20',
            ],
        ];

        $this->assertEquals($expected, $converted);
    }
}
