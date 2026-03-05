<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\Hydrator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function Typhoon\Type\arrayShapeT;

use function Typhoon\Type\arrayT;
use function Typhoon\Type\listT;
use function Typhoon\Type\unsealedArrayShapeT;

use const Typhoon\Type\intT;
use const Typhoon\Type\stringT;

final class HydratorArrayTest extends TestCase
{
    #[Test]
    public function array_shape_sealed(): void
    {
        $sealed = ['x' => 1];
        $r = (new Hydrator())->hydrate($sealed, arrayShapeT(['x' => intT]));
        assertEquals($sealed, $r);
    }

    #[Test]
    public function array_shape_unsealed(): void
    {
        $unsealed = ['x' => 1, 'y' => 2];
        $r = (new Hydrator())->hydrate($unsealed, unsealedArrayShapeT(['x' => intT], stringT, intT));
        assertEquals($unsealed, $r);
    }

    #[Test]
    public function just_array(): void
    {
        $arr = ['x' => 1, 'y' => 2];
        $r = (new Hydrator())->hydrate($arr, arrayT(stringT, intT));
        assertEquals($arr, $r);
    }

    #[Test]
    public function test_list(): void
    {
        $arr = ['x' => 1, 'y' => 2];
        $r = (new Hydrator())->hydrate($arr, listT(intT));
        assertEquals([1, 2], $r);
    }
}