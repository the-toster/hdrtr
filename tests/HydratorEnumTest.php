<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\Hydrator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function Typhoon\Type\objectT;

final class HydratorEnumTest extends TestCase
{
    #[Test]
    public function unit_enum(): void
    {
        $r = (new Hydrator())->hydrate('a', objectT(BasicEnum::class));

        assertEquals(BasicEnum::a, $r);
    }

    #[Test]
    public function backed_enum(): void
    {
        $r = (new Hydrator())->hydrate('active', objectT(BackedEnum::class));

        assertEquals(BackedEnum::act, $r);
    }
}
