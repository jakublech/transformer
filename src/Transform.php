<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer;

use JakubLech\Transformer\Exception\UnsupportedTransformationException;
use JakubLech\Transformer\Transformers\TransformerInterface;

final class Transform
{
    /** @var TransformerInterface[] | array<string, TransformerInterface> */
    private array $transformers = [];

    /** @var array<string, array<int, string>> */
    private array $classInheritanceCache = [];

    /** @param iterable|TransformerInterface[] $transformers */
    public function __construct(iterable $transformers = [])
    {
        foreach ($transformers as $transformer) {
            $this->add($transformer);
        }
    }

    /**
     * @throws UnsupportedTransformationException
     */
    public function __invoke(mixed $input, string $outputType, array $context = []): mixed
    {
        return $this->get($input, $outputType)->__invoke($input, $context);
    }

    public function add(TransformerInterface $transformer): void
    {
        $key = $this->getTransformerKeyPair($transformer->inputType(), $transformer->returnType());
        if (!isset($this->transformers[$key])
            || $this->transformers[$key]->priority() < $transformer->priority()) {
            $this->transformers[$key] = $transformer;
        }
    }

    /**
     * @throws UnsupportedTransformationException
     */
    public function get(mixed $input, string $outputType): TransformerInterface
    {
        foreach ($this->getInheritedTypes($input) as $inputType) {
            if ($transformer = $this->transformers[$this->getTransformerKeyPair($inputType, $outputType)] ?? false) {
                return $transformer;
            }
        }

        throw new UnsupportedTransformationException(sprintf(
            'No transformer from %s to %s. Registered transformers are: [%s]',
            $inputType,
            $outputType,
            implode(', ', array_keys($this->transformers)),
        ));
    }

    public function find(mixed $input, string $outputType): ?TransformerInterface
    {
        try {
            return $this->get($input, $outputType);
        } catch (UnsupportedTransformationException $e) {
            return null;
        }
    }

    /**
     * @return array<int, string>
     */
    private function getInheritedTypes(mixed $input): array
    {
        return 'object' !== ($inputType = gettype($input))
            ? [$inputType]
            : $this->classInheritanceCache[$input::class] ??= [
                $input::class,
                ...class_parents($input) ?: [],
                ...array_reverse(class_implements($input) ?: []),
                'object',
            ];
    }

    private function getTransformerKeyPair(string $inputType, string $outputType): string
    {
        return $inputType.':to:'.$outputType;
    }
}
