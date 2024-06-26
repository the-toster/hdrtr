<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\Type;

use Typhoon\Type\types;

use function Typhoon\TypeComparator\isSubtype;

final class MixedH extends DefaultTypeHydrator
{
    public function supports(Type $type): bool
    {
        return isSubtype($type, types::mixed);
    }

    public function mixed(Type $self): mixed
    {
        return fn(HydratorSelector $hydrator): mixed => $hydrator->data;
    }
}