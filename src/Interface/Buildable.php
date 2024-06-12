<?php

declare(strict_types=1);

namespace JakubLech\Converter\Interface;

interface Buildable
{
    public function build($object, string $format, array $context = []): mixed;
}
