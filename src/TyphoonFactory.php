<?php

declare(strict_types=1);

namespace Hdrtr;

use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use Typhoon\Type;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ConstantT;

use Typhoon\Type\IntersectionT;
use Typhoon\Type\NamedObjectT;
use Typhoon\Type\ObjectT;

use Typhoon\Type\UnionT;

use function Typhoon\Type\classConstantT;
use function Typhoon\Type\constantT;
use function Typhoon\Type\intersectionT;
use function Typhoon\Type\objectT;
use function Typhoon\Type\unionT;

final readonly class TyphoonFactory
{
    public static function constant(ConstFetchNode $node): ConstantT|ClassConstantT
    {
        $name = $node->name;
        if ($name === '') {
            throw new \RuntimeException('Constant name cannot be empty');
        }

        if ($node->className === '') {
            return constantT($node->name);
        }


        /** @var class-string */
        $className = $node->className;

        return classConstantT($className, $name);
    }

    public static function object(string $className): NamedObjectT
    {
        /** @var class-string $className */
        return objectT($className);
    }

    /**
     * @param array<Type> $types
     */
    public static function intersection(array $types): IntersectionT
    {
        if($types === []) {
            throw new \RuntimeException();
        }
        return intersectionT(array_values($types));
    }

    /**
     * @param array<Type> $types
     */
    public static function union(array $types): UnionT
    {
        if($types === []) {
            throw new \RuntimeException();
        }
        return unionT(array_values($types));
    }
}