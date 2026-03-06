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

final class HydratorNamespaceTest extends TestCase
{
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