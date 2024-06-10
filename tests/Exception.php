<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Functional\tests;

use JakubLech\Converter\ConverterProvider;

class ExceptionConvertExample
{
    public function __construct(private ConverterProvider $convert)
    {
    }

    public function test(): void
    {
        $object = new \Exception('message', 300);
        $this->convert->provide($object)->convert($object, 'array');
        $this->convert->provide(new \Exception('message', 300), 'json');
        $this->convert->provide(new \Exception('message', 300), 'jsonResponse', ['_status' => 200, '_header' => ['Content-Type' => 'application/json']]);
        $this->convert->provide(new \RuntimeException('message', 300), 'jsonResponse');

    }
}
