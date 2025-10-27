<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\TypesTransformer\Throwable;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\Transformer;
use JakubLech\Transformer\TypesTransformer\TypesTransformerInterface;
use Throwable;
use DateTimeImmutable;

final readonly class ThrowableToArrayTypesTransformer implements TypesTransformerInterface
{
    public function __construct(private Transformer $transform, private bool $debug = false)
    {
    }

    /**
     * @param Throwable $input
     *
     * @throws UnsupportedInputTypeException
     */
    public function __invoke(mixed $input, array $context = []): array
    {
        AssertInputType::strict($input, $this);

        if (false === $this->debug) {
            return [
                'error' => $input->getMessage(),
                'code' => $input->getCode(),
            ];
        }

        return [
            'message' => $input->getMessage(),
            'timestamp' => ($this->transform)(new DateTimeImmutable(), 'array', $context),
            'class' => $input::class,
            'status' => $input->getCode(),
            'file' => $input->getFile(),
            'line' => $input->getLine(),
            'trace' => $input->getTrace(),
            'previous' => $input->getPrevious() ? ($this->transform)($input->getPrevious(), 'array', $context) : null,
        ];
    }

    public static function inputType(): string
    {
        return Throwable::class;
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
