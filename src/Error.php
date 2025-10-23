<?php

declare(strict_types=1);

namespace Hdrtr;

final readonly class Error
{
    public static function create(): self
    {
        return new self();
    }

}