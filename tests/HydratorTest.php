<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\Hydrator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function Typhoon\Type\objectT;
use const Typhoon\Type\intT;

final class HydratorTest extends TestCase
{
    #[Test]
    public function it_can_do_basics(): void
    {
        $hydrator = new Hydrator();
        assertEquals(1, $hydrator->hydrate(1, intT));
    }

    #[Test]
    public function it_can_hydrate_generic(): void
    {
        $hydrator = new Hydrator();
        $data = ['items' => [1, 2, 3]];

        assertEquals(new Collection([1, 2, 3]), $hydrator->hydrate($data, objectT(Collection::class, [intT])));
    }
}