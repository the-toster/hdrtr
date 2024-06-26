<?php

declare(strict_types=1);

namespace Tests\Infrastructure\TyphoonHydrator;

use App\Infrastructure\TyphoonHydrator\Error;
use App\Infrastructure\TyphoonHydrator\Hydrator;
use PHPUnit\Framework\TestCase;
use Typhoon\Type\Type;
use Typhoon\Type\types;

use function PHPUnit\Framework\assertEquals;

final class HydratorTest extends TestCase
{

    /** @test */
    public function it_can_hydrate_scalar(): void
    {
        $this->assertCanHydrateScalar(123, types::int);
        $this->assertCanHydrateScalar(null, types::nullable(types::int));
        $this->assertCanHydrateScalar(123, types::nullable(types::int));
        $this->assertCanHydrateScalar(null, types::null);
        $this->assertCanHydrateScalar(123.45, types::float);
        $this->assertCanHydrateScalar('abc', types::string);
        $this->assertCanHydrateScalar(true, types::true);
        $this->assertCanHydrateScalar(false, types::false);
        $this->assertCanHydrateScalar(true, types::bool);
    }

    /** @test */
    public function it_can_hydrate_nullable_scalar(): void
    {
        $this->assertCanHydrateScalar(null, types::nullable(types::int));
    }

    /** @test */
    public function it_can_hydrate_literal_scalar(): void
    {
        $this->assertCanHydrateScalar(true, types::true);
    }

    /** @test */
    public function it_can_hydrate_int_as_float(): void
    {
        $this->assertCanHydrateScalar(123, types::float);
    }

    private function assertCanHydrateScalar(mixed $data, Type $type): void
    {
        /** @psalm-suppress MixedAssignment */
        $result = (new Hydrator())->hydrate($data, $type);
        if ($result instanceof Error) {
            self::fail($result->toString());
        }
        assertEquals($data, $result);
    }

    /** @test */
    public function it_can_hydrate_basic_object(): void
    {
        $object = new BasicObject('A');
        $this->assertHydrate(['v' => 'A'], types::object(BasicObject::class), $object);
    }

    /**
     * @test
     */
    public function it_can_hydrate_collection_object(): void
    {
        $object = new ObjectWithCollection([new CollectionItem(1), new CollectionItem(100)]);
        $this->assertHydrate(
            data: ['items' => [['id' => 1], ['id' => 100]]],
            type: types::object(ObjectWithCollection::class),
            expected: $object
        );
    }

    /** @test */
    public function it_can_hydrate_with_default_promoted_property(): void
    {
        $object = new ObjectWithDefaultPromotedProperty([1, 2, 3]);

        $result = (new Hydrator())->hydrate([], types::object(ObjectWithDefaultPromotedProperty::class));

        if ($result instanceof Error) {
            self::fail($result->toString());
        }

        assertEquals($object, $result);
        assertEquals([1, 2, 3], $result->items);
    }


    private function assertHydrate(mixed $data, Type $type, mixed $expected): void
    {
        /** @psalm-suppress MixedAssignment */
        $result = (new Hydrator())->hydrate($data, $type);
        if ($result instanceof Error) {
            self::fail($result->toString());
        }
        assertEquals($expected, $result);
    }
}