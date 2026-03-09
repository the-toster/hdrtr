<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\CustomHydrator;
use Hdrtr\Hydrator;
use Typhoon\Type;

final readonly class TestCustomHydrator implements CustomHydrator
{

    public function hydrate(mixed $data, Type $type, array $path, Hydrator $hydrator): string
    {
        /** @phpstan-ignore cast.string */
        return (string) $data;
    }

    public function supports(mixed $data, Type $type): bool
    {
        return $type instanceof Type\StringT
            && is_int($data);
    }
}