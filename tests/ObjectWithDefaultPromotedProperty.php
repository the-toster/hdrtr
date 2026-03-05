<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

/**
 * @template T = int
 */
final readonly class ObjectWithDefaultPromotedProperty
{
    /**
     * @param array<T> $items
     */
    public function __construct(
        public array $items = [1, 2, 3],
    ) {
    }
}