<?php

declare(strict_types=1);

namespace Hdrtr;

use ReflectionClass;
use Typhoon\Type\NamedObjectT;

final readonly class ObjectInstantiator
{
    /**
     * @param array<mixed> $data
     */
    public function buildInstance(NamedObjectT $type, array $data, HydratingVisitor $hydrator): object
    {
        $reflection = new ReflectionClass($type->class);
        $constructorDefaults = $this->getConstructorDefaults($reflection);
        $docBlockParser = new DocBlockParser();

        $classTemplates = $docBlockParser->parseTemplates($reflection->getDocComment());
        $templateArguments = [];
        foreach ($classTemplates as $index => $template) {
            $templateArguments[$template->templateName] = $type->templateArguments[$index] ?? $template->default;
        }

        $r = $reflection->newInstanceWithoutConstructor();
        foreach ($reflection->getProperties() as $property) {
            if (!array_key_exists($property->name, $data)) {
                if ($property->hasDefaultValue()) {
                    continue;
                }

                if ($property->isPromoted() && array_key_exists($property->name, $constructorDefaults)) {
                    $property->setValue($r, $constructorDefaults[$property->name]);
                    continue;
                }

                return $hydrator->errorMissedKey($type, $property->name);
            }


            $propertyType = $docBlockParser->parseVar($property->getDocComment(), $templateArguments)
                ?? (new ReflectionTypeConverter())->convert($property->getType());

            $propertyHydrationResult = $propertyType->accept($hydrator->forOffset($property->name));

            if ($propertyHydrationResult instanceof Error) {
                return $propertyHydrationResult;
            }

            $property->setValue($r, $propertyHydrationResult);
        }

        return $r;
    }

    /**
     * @template T of object
     * @param ReflectionClass<T> $reflectionClass
     * @return array<mixed>
     */
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