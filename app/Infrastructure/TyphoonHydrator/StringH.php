<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\Type;

use Typhoon\Type\types;

use function Typhoon\TypeComparator\isSubtype;

final class StringH extends DefaultTypeHydrator
{
    public function supports(Type $type): bool
    {
        return isSubtype($type, types::string);
    }

    public function string(Type $self): mixed
    {
        return fn(HydratorSelector $hydratorSelector) => is_string($hydratorSelector->data)
            ? $hydratorSelector->data
            : $hydratorSelector->unexpectedValue($self);
    }
}