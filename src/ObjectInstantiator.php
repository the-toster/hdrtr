<?php

declare(strict_types=1);

namespace Hdrtr;

use ReflectionClass;
use Typhoon\Type\NamedObjectT;

final readonly class ObjectInstantiator
{
    public function buildInstance(NamedObjectT $type, array $data, HydratingVisitor $hydrator): object
    {
        $reflection = new ReflectionClass($type->class);
        $constructorDefaults = $this->getConstructorDefaults($reflection);
        $propertyReflector = new DocBlockReflector($type);

        $r = $reflection->newInstanceWithoutConstructor();
        foreach ($reflection->getProperties() as $property) {
            if (!array_key_exists($property->name, $data)) {
                if (!$property->hasDefaultValue()) {
                    continue;
                }

                if ($property->isPromoted() && array_key_exists($property->name, $constructorDefaults)) {
                    $property->setValue($r, $constructorDefaults[$property->name]);
                    continue;
                }

                return $hydrator->errorMissedKey($type, $property->name);
            }

            $propertyType = $propertyReflector->reflect($property)
                ?? (new ReflectionTypeConverter())->convert($property->getType());

            $propertyHydrationResult = $propertyType->accept($hydrator->forOffset($data[$property->name]));

            if ($propertyHydrationResult instanceof Error) {
                return $propertyHydrationResult;
            }

            $property->setValue($r, $propertyHydrationResult);
        }

        return $r;
    }

    private function getConstructorDefaults(\ReflectionClass $reflectionClass): array
    {
        /**
         * @var \ReflectionParameter[] $params
         */
        $params = $reflectionClass->getConstructor()?->getParameters() ?? [];
        $r = [];
        foreach ($params as $param) {
            if ($param->isDefaultValueAvailable()) {
                $r[$param->name] = $param->getDefaultValue();
            }
        }

        return $r;
    }
}