<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers\DateTime;

use DateTimeInterface;
use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\TransformException;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformers\TransformerInterface;

final class DateTimeInterfaceToArrayTransformer implements TransformerInterface
{
    private const DEFAULT_FORMAT = 'Y-m-d H:i:s.u';

    public static function inputType(): string { return DateTimeInterface::class;}

    public static function returnType(): string { return 'array';}

    /**
     * @param DateTimeInterface $input
     *
     * @throws TransformException|UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);
        $format = $context['_dateFormat'] ?? self::DEFAULT_FORMAT;

        return [
            'date' => $input->format($format),
            'timezone' => $input->getTimezone()->getName(),
        ];
    }
}
