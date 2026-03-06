<?php

declare(strict_types=1);

namespace Hdrtr;

use ReflectionEnum;
use Typhoon\Type\NamedObjectT;

final readonly class EnumInstantiator
{

    public function buildInstance(NamedObjectT $type, mixed $data, HydratingVisitor $hydrator): mixed
    {
        /** @var class-string<\UnitEnum|\BackedEnum> $class */
        $class = $type->class;
        $ref = new ReflectionEnum($class);

        foreach ($ref->getCases() as $case) {
            /** @phpstan-ignore method.notFound  */
            $val = $ref->isBacked() ? $case->getBackingValue() : $case->name;
            if ($val === $data) {
                return $case->getValue();
            }
        }

        return $hydrator->failedToCast($type);
    }

}
