<?php

declare(strict_types=1);

namespace JakubLech\Converter\Interface;

use Symfony\Component\HttpFoundation\JsonResponse;

interface ToJsonResponse
{
    public function jsonResponse($object, array $context = []): JsonResponse;
}
