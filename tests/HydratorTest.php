<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\Hydrator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use Typhoon\Type\IntT;

use function PHPUnit\Framework\assertEquals;

final class HydratorTest extends TestCase
{
    #[Test]
    public function it_can_do_basics(): void
    {
        $hydrator = new Hydrator();
        assertEquals(1, $hydrator->hydrate(1, IntT::T));
    }
}