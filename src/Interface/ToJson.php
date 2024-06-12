<?php

declare(strict_types=1);

namespace JakubLech\Converter\Interface;

interface ToJson
{
    public function json($object, array $context = []): ?string;
}
