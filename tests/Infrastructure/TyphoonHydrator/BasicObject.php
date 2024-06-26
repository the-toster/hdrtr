<?php

declare(strict_types=1);

namespace Tests\Infrastructure\TyphoonHydrator;

final readonly class BasicObject
{
    public function __construct(
        public string $v,
    )
    {
    }
}