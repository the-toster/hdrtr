<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\Type;

use Typhoon\Type\types;

use function Typhoon\TypeComparator\isSubtype;

final class FloatH extends DefaultTypeHydrator
{
    public function supports(Type $type): bool
    {
        return isSubtype($type, types::float);
    }

    public function float(Type $self): mixed
    {
        return fn(HydratorSelector $hydrator) => is_float($hydrator->data) || is_int($hydrator->data)
            ? $hydrator->data
            : $hydrator->unexpectedValue($self);
    }
}