<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\TransformException;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transform;
use IteratorAggregate;

final class DateTimeInterfaceToArrayTransformer implements TransformerInterface
{
    /**
     * @param \DateTimeInterface $input
     * @throws TransformException | UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        return [
            'date' => $input->format('Y-m-d H:i:s.u'),
            'timezone_type' => 3,
            'timezone' => $input->getTimezone()->getName(),
        ];
    }

    public static function inputType(): string
    {
        return \DateTimeInterface::class;
    }

    public static function returnType(): string
    {
        return 'array';
    }

    public static function priority(): int
    {
        return -1000;
    }
}
