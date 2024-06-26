<?php

declare(strict_types=1);

namespace Tests\Infrastructure\TyphoonHydrator;


final readonly class ObjectWithDefaultPromotedProperty
{
    public function __construct(
        public array $items = [1, 2, 3],
    ) {
    }
}