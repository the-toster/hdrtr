<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\Type;

use Typhoon\Type\types;

use function Typhoon\TypeComparator\isSubtype;

final class IntH extends DefaultTypeHydrator
{
    public function supports(Type $type): bool
    {
        return isSubtype($type, types::int);
    }

    public function int(Type $self): mixed
    {
        return fn(HydratorSelector $hydrator) => is_int($hydrator->data)
            ? $hydrator->data
            : $hydrator->unexpectedValue($self);
    }
}