<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use App\Infrastructure\Uuid;
use Typhoon\Type\Type;

use Typhoon\Type\types;

use function Typhoon\TypeComparator\isSubtype;

final class UuidH extends DefaultTypeHydrator
{
    public function supports(Type $type): bool
    {
        return isSubtype($type, types::object(Uuid::class));
    }

    public function namedObject(Type $self, string $class, array $arguments): mixed
    {
        return fn(HydratorSelector $hydrator): Uuid|Error => $this->hydrate($self, $hydrator);
    }

    public function hydrate(Type $self, HydratorSelector $hydrator): Uuid|Error
    {
        if (is_string($hydrator->data) && Uuid::isValid($hydrator->data)) {
            return Uuid::fromString($hydrator->data);
        }


        if (
            is_array($hydrator->data)
            && isset($hydrator->data['uuid'])
            && is_string($hydrator->data['uuid'])
            && Uuid::isValid($hydrator->data['uuid'])
        ) {
            return Uuid::fromString($hydrator->data['uuid']);
        }

        return $hydrator->unexpectedValue($self);
    }

}