<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

final readonly class ObjectWithDefaultPromotedProperty
{
    /**
     * @param array<int> $items
     */
    public function __construct(
        public array $items = [1, 2, 3],
    ) {
    }
}