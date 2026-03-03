<?php

declare(strict_types=1);

namespace Hdrtr;

use \RuntimeException;

final class ReflectionError
{
    public function __construct(
        public readonly string $docBlock,
        public readonly string $errorMessage,
    )
    {
    }
}