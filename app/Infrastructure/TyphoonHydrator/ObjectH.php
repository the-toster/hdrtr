<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Reflection\ClassReflection;
use Typhoon\Reflection\PropertyReflection;
use Typhoon\Reflection\TyphoonReflector;
use Typhoon\Type\Type;

use Typhoon\Type\types;

use function Typhoon\TypeComparator\isSubtype;

final class ObjectH extends DefaultTypeHydrator
{
    public function supports(Type $type): bool
    {
        return isSubtype($type, types::object);
    }

    public function namedObject(Type $self, string $class, array $arguments): mixed
    {
        return fn(HydratorSelector $hydratorSelector): mixed => $this->hydrate($hydratorSelector, $class);
    }

    private function hydrate(
        HydratorSelector $hydratorSelector,
        string $class,
    ): mixed {
        $reflection = TyphoonReflector::build()->reflectClass($class);
        $result = $reflection->newInstanceWithoutConstructor();

        foreach ($reflection->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $offset = $property->getName();
            $hasOffset = isset($hydratorSelector->data[$offset]);
            $hasDefaultValue = $property->hasDefaultValue();

            if (!$hasOffset && !$hasDefaultValue) {
                return $hydratorSelector->missedOffset($offset);
            }

            /**
             * @psalm-suppress MixedAssignment
             */
            $value = $hasDefaultValue
                ? $this->getPropertyDefaultValue($reflection, $property)
                : $property->getTyphoonType()->accept($hydratorSelector->next($offset));

            if ($value instanceof Error) {
                return $value;
            }

            $property->setValue($result, $value);
        }

        return $result;
    }

    private function getPropertyDefaultValue(ClassReflection $classReflection, PropertyReflection $property): mixed
    {
        return $property->isPromoted()
            ? ($classReflection->getConstructor() ?? throw new \LogicException())
                ->getParameter($property->getName())
                ->getDefaultValue()
            : $property->getDefaultValue();
    }
}