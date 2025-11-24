<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\Type;

final readonly class ReflectionTypeConverter
{
    public function convert(\ReflectionType $ref): Type
    {
        return match ($ref::class) {
            \ReflectionNamedType::class => $this->named($ref),
        };
    }

    private function named(\ReflectionNamedType $ref): Type
    {
        $typeName = $ref->getName();
        return match ($typeName) {
            'never' => Type\neverT,
            'void' => Type\voidT,
            'null' => Type\nullT,
            'true' => Type\trueT,
            'false' => Type\falseT,
            'bool' => Type\boolT,
            'int' => Type\intT,
            'float' => Type\floatT,
            'string' => Type\stringT,
            'array' => Type\arrayT,
            'iterable' => Type\iterableT,
            'object' => Type\objectT,
            'callable' => Type\callableT,
            'resource' => Type\resourceT,
            'mixed' => Type\mixedT,
            default => class_exists($typeName)
                ? new Type\NamedObjectT($typeName)
                : Type\objectT,
        };
    }
}