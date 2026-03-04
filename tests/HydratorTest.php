<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\Error;
use Hdrtr\Hydrator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function Typhoon\Type\objectT;
use const Typhoon\Type\intT;
use const Typhoon\Type\stringT;

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

    #[Test]
    public function it_can_hydrate_promoted_properties(): void
    {
        $hydrator = new Hydrator();
        $data = ['items' => [4, 5]];

        assertEquals(new ObjectWithDefaultPromotedProperty([4, 5]), $hydrator->hydrate($data, objectT(ObjectWithDefaultPromotedProperty::class)));
    }

    #[Test]
    public function it_can_hydrate_promoted_properties_with_defaults(): void
    {
        $hydrator = new Hydrator();
        $data = [];

        assertEquals(new ObjectWithDefaultPromotedProperty([1, 2, 3]), $hydrator->hydrate($data, objectT(ObjectWithDefaultPromotedProperty::class)));
    }

    #[Test]
    public function it_respects_promoted_property_annotation(): void
    {
        $hydrator = new Hydrator();
        $data = ['items' => ['a', 'b', 'c']];

        assertEquals(
            Error::failedToCast(stringT, 'a', ['items', '0']),
            $hydrator->hydrate($data, objectT(ObjectWithDefaultPromotedProperty::class))
        );
    }
}