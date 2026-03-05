<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\Error;
use Hdrtr\Hydrator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

use const Typhoon\Type\stringT;

final class HydratorCustomTest extends TestCase
{
    #[Test]
    public function test_custom(): void
    {
        $r = (new Hydrator())->hydrate(123, stringT);
        assertInstanceOf(Error::class, $r);

        $r = (new Hydrator([new TestCustomHydrator()]))->hydrate(123, stringT);
        assertEquals('123', $r);
    }

}