<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\Type;

final readonly class ReflectionTypeConverter
{
    public function convert(?\ReflectionType $ref): Type
    {
        if ($ref === null) {
            return Type\mixedT;
        }

        return match ($ref::class) {
            \ReflectionNamedType::class => $this->named($ref),
            \ReflectionUnionType::class => $this->union($ref),
            \ReflectionIntersectionType::class => $this->intersection($ref),
            default => throw new \RuntimeException()
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
            default => TyphoonFactory::object($typeName)
        };


    }

    private function intersection(\ReflectionIntersectionType $ref): Type
    {
        $r = [];
        foreach ($ref->getTypes() as $type) {
            $r[] = $this->convert($type);
        }

        if(count($r) === 0) {
            return Type\neverT;
        }

        return Type\intersectionT($r);
    }

    private function union(\ReflectionUnionType $ref): Type
    {
        $r = [];
        foreach ($ref->getTypes() as $type) {
            $r[] = $this->convert($type);
        }

        if(count($r) === 0) {
            return Type\neverT;
        }

        return Type\unionT($r);
    }
}