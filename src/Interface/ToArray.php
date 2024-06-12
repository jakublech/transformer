<?php

declare(strict_types=1);

namespace JakubLech\Converter\Interface;

interface ToArray
{
    public function array($object, array $context = []): ?array;
}
