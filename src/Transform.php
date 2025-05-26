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
    /** @var array<string, array<string, TransformerInterface>> */
    private array $transformers = [];

    private array $classCache = [];

    /** @param iterable|TransformerInterface[] $transformers */
    public function __construct(iterable $transformers = [])
    {
        foreach ($transformers as $transformer) {
            $this->addTransformer($transformer);
        }
    }

    /**
     * @throws UnsupportedTransformationException
     */
    public function __invoke(mixed $input, string $outputType, array $context = []): mixed
    {
        return $this->getTransformer($input, $outputType)->__invoke($input, $context);
    }

    public function addTransformer(TransformerInterface $transformer): void
    {
        $transformerKeyPair = $this->getTransformerKeyPair($transformer->inputType(), $transformer->returnType());
        if (!isset($this->transformers[$transformerKeyPair])
            || ($this->transformers[$transformerKeyPair]->priority() < $transformer->priority())) {
            $this->transformers[$transformerKeyPair] = $transformer;
        }
    }

    /**
     * @throws UnsupportedTransformationException
     */
    public function getTransformer(mixed $input, string $outputType): TransformerInterface
    {
        $inputType = gettype($input);
        if ('object' === $inputType) {
            foreach ($this->getClassTypes($input) as $inputType) {
                if ($transformer = $this->transformers[$this->getTransformerKeyPair($type, $outputType)] ?? null) {
                    return $transformer;
                }
            }
        }

        // for simple type
        $transformerKeyPair = $this->getTransformerKeyPair($inputType, $outputType);

        return $this->transformers[$transformerKeyPair]
            ?? throw new UnsupportedTransformationException(sprintf(
                'No transformer from %s to %s. Registered: %s',
                $inputType,
                $outputType,
                implode(', ', array_keys($this->transformers)),
            ));
    }

    public function supports(mixed $input, string $outputType): bool
    {
        return (bool) $this->findTransformer($input, $outputType);
    }

    public function findTransformer(mixed $input, string $outputType): ?TransformerInterface
    {
        try {
            return $this->getTransformer($input, $outputType);
        } catch (UnsupportedTransformationException $e) {
            return null;
        }
    }

    /**
     * @return array<int, string>
     */
    private function getClassTypes(object $input): array
    {
        $class = get_class($input);

        return $this->classCache[$class] ??= [
            $class,
            ...(array) class_parents($input) ?: [],
            ...(array) class_implements($input) ?: [],
        ];
    }

    private function getTransformerKeyPair(string $inputType, string $outputType): string
    {
        return sprintf('%s:%s', $inputType, $outputType);
    }
}
