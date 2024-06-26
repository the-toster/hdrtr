<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\Type;

use Typhoon\Type\types;

use function Typhoon\TypeComparator\isSubtype;

final class NullH extends DefaultTypeHydrator
{
    public function supports(Type $type): bool
    {
        return isSubtype($type, types::null);
    }

    public function null(Type $self): mixed
    {
        return fn(HydratorSelector $hydrator) => is_null($hydrator->data)
            ? $hydrator->data
            : $hydrator->unexpectedValue($self);
    }
}