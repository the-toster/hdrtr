<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\Type;

use Typhoon\Type\types;

use function Typhoon\TypeComparator\isSubtype;

final class BoolH extends DefaultTypeHydrator
{
    public function supports(Type $type): bool
    {
        return isSubtype($type, types::bool);
    }

    public function bool(Type $self): \Closure
    {
        return fn(HydratorSelector $hydrator): bool|Error => is_bool($hydrator->data)
            ? $hydrator->data
            : $hydrator->unexpectedValue($self);
    }

    public function literalValue(Type $self, bool|int|float|string $value): mixed
    {
        return fn(HydratorSelector $hydrator) => $hydrator->data === $value
            ? $hydrator->data
            : $hydrator->unexpectedValue($self);
    }
}