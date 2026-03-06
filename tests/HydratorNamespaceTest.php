<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\Hydrator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function Typhoon\Type\objectT;

final class HydratorNamespaceTest extends TestCase
{
    #[Test]
    public function partially_qualified_name(): void
    {
        $r = (new Hydrator())->hydrate(
            ['item' => ['value' => 'hello']],
            objectT(WithPartiallyQualifiedName::class)
        );

        $expected = new Sub\Deep\Deep\SimpleObject();
        $expected->value = 'hello';

        assertEquals(
            new WithPartiallyQualifiedName($expected),
            $r
        );
    }

    #[Test]
    public function collection_of_objects(): void
    {
        $r = (new Hydrator())->hydrate(
            [
                'items' => [[], []],
                'aliasedItems' => [[]]
            ],
            objectT(CollectionOfObjects::class)
        );

        assertEquals(
            new CollectionOfObjects(
                [
                    new ObjectWithDefaultProperty(),
                    new ObjectWithDefaultProperty()
                ],
                [
                    new ObjectWithDefaultProperty(),
                ],
            ),
            $r
        );
    }
}