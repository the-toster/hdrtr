<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

/**
 * @template T
 */
final readonly class Collection
{
    /**
     * @var array<T>
     */
    public array $items;

    /**
     * @param array<T> $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }
}