<?php

declare(strict_types=1);

namespace QueryResponseInterface;

use Examples\Converter\QueryResponseInterface\ConverterProviderInterface;
use Examples\Converter\QueryResponseInterface\JsonResponse;
use Examples\Converter\QueryResponseInterface\QueryInterface;
use Examples\Converter\QueryResponseInterface\SerializerInterface;
use JakubLech\Converter\ConverterClassAbstract;

class QueryResponseInterface extends ConverterClassAbstract
{
    protected const string CONVERTER_FOR_CLASSNAME = QueryInterface::class;

    public function __construct(private ConverterProviderInterface $converterProvider, private SerializerInterface $symfonySerializer)
    {
        parent::__construct($converterProvider);


        $this->supportFormat('array', fn (QueryInterface $query, array $context = []) => $this->array($query, $context));
        $this->supportFormat('json', fn (QueryInterface $class, array $context = []) => json_encode($this->build($class, 'array', $context)));
        $this->supportFormat('jsonResponse', fn (QueryInterface $class, array $context = []) => $this->jsonResponse($class, $context));
    }

    public function array(QueryInterface $class, array $context = []): array
    {
        return $this->symfonySerializer->normalize($class, null, $context);
    }
    public function jsonResponse(QueryInterface $class, array $context = []): JsonResponse
    {
        return new JsonResponse(
            $this->build($class, 'array', $context),
            $context['_status'] ?? 200,
            $context['_header'] ?? ['Content-Type' => 'application/json']
        );
    }
}
