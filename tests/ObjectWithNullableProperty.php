<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

final readonly class ObjectWithNullableProperty
{
    public function __construct(
        public ?string $a,
    ) {
    }
}
