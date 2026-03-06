<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\Tests\ObjectWithDefaultProperty as TestAlias;

final readonly class CollectionOfObjects
{
    /**
     * @param list<ObjectWithDefaultProperty> $items
     * @param list<TestAlias> $aliasedItems
     */
    public function __construct(
        public array $items,
        public array $aliasedItems,

    ) {
    }
}