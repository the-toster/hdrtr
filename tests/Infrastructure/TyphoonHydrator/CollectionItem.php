<?php

declare(strict_types=1);

namespace Tests\Infrastructure\TyphoonHydrator;

final readonly class CollectionItem
{
    public function __construct(
        public int $id,
    )
    {
    }
}