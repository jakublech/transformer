<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer;


final readonly class TransformCommand
{
    public function __construct(
        public mixed $input,
        public string $outputType,
        public array $context = [],
    ) {}
}
