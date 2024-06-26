<?php

declare(strict_types=1);

namespace Tests\Infrastructure\TyphoonHydrator;

final readonly class ObjectWithCollection
{
    /**
     * @param  list<CollectionItem>  $items
     */
    public function __construct(
        public array $items,
    ) {
    }
}