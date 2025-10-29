<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers\Throwable;

use JakubLech\Transformer\Assert\AssertInputType;
use JakubLech\Transformer\Exception\UnsupportedInputTypeException;
use JakubLech\Transformer\TransformHandler;
use JakubLech\Transformer\Transformers\TransformerInterface;
use Throwable;
use DateTimeImmutable;

final readonly class ThrowableToArrayTransformer implements TransformerInterface
{
    public function __construct(private TransformHandler $transform, private bool $debug = false){}

    public static function inputType(): string { return Throwable::class;}

    public static function returnType(): string { return 'array';}

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
            'timestamp' => $this->transform->transform(new DateTimeImmutable(), 'array', $context),
            'class' => $input::class,
            'status' => $input->getCode(),
            'file' => $input->getFile(),
            'line' => $input->getLine(),
            'trace' => $input->getTrace(),
            'previous' => $input->getPrevious() ? $this->transform->transform($input->getPrevious(), 'array', $context) : null,
        ];
    }
}
