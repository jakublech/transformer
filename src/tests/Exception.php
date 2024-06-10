<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use JakubLech\Converter\Builder;

class ExceptionConvertExample
{
    public function __construct(private Builder $convert)
    {
    }

    public function test(): void
    {
        $this->convert->build(new \Exception('message', 300), 'array');
        $this->convert->build(new \Exception('message', 300), 'json');
        $this->convert->build(new \Exception('message', 300), 'jsonResponse', ['_status' => 200, '_header' => ['Content-Type' => 'application/json']]);
        $this->convert->build(new \RuntimeException('message', 300), 'jsonResponse');

    }
}
