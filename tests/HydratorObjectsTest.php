<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\Error;
use Hdrtr\Hydrator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function Typhoon\Type\arrayShapeT;
use function Typhoon\Type\objectShapeT;
use function Typhoon\Type\objectT;
use const Typhoon\Type\intT;
use const Typhoon\Type\stringT;

final class HydratorObjectsTest extends TestCase
{
    #[Test]
    public function generic(): void
    {
        $hydrator = new Hydrator();
        $data = ['items' => [1, 2, 3]];

        assertEquals(new Collection([1, 2, 3]), $hydrator->hydrate($data, objectT(Collection::class, [intT])));
    }

    #[Test]
    public function properties_with_defaults(): void
    {
        $hydrator = new Hydrator();
        $data = [];

        assertEquals(new ObjectWithDefaultProperty(), $hydrator->hydrate($data, objectT(ObjectWithDefaultProperty::class)));
    }

    #[Test]
    public function promoted_properties(): void
    {
        $hydrator = new Hydrator();
        $data = ['items' => [4, 5]];

        assertEquals(new ObjectWithDefaultPromotedProperty([4, 5]), $hydrator->hydrate($data, objectT(ObjectWithDefaultPromotedProperty::class)));
    }

    #[Test]
    public function promoted_properties_with_defaults(): void
    {
        $hydrator = new Hydrator();
        $data = [];

        assertEquals(new ObjectWithDefaultPromotedProperty([1, 2, 3]), $hydrator->hydrate($data, objectT(ObjectWithDefaultPromotedProperty::class)));
    }

    #[Test]
    public function respect_property_annotation(): void
    {
        $hydrator = new Hydrator();
        $data = ['items' => ['a', 'b', 'c']];

        assertEquals(
            Error::failedToCast(intT, 'a', ['items', '0']),
            $hydrator->hydrate($data, objectT(ObjectWithDefaultProperty::class))
        );
    }

    #[Test]
    public function respect_promoted_property_annotation(): void
    {
        $hydrator = new Hydrator();
        $data = ['items' => ['a', 'b', 'c']];

        assertEquals(
            Error::failedToCast(intT, 'a', ['items', '0']),
            $hydrator->hydrate($data, objectT(ObjectWithDefaultPromotedProperty::class))
        );

        assertEquals(
            new ObjectWithDefaultPromotedProperty(['a', 'b', 'c']),
            $hydrator->hydrate($data, objectT(ObjectWithDefaultPromotedProperty::class, [stringT]))
        );
    }

    #[Test]
    public function object_shape(): void
    {
        $r = (new Hydrator())->hydrate(['x' => 1], objectShapeT(['x' => intT]));
        assertEquals((object)['x' => 1], $r);
    }
}