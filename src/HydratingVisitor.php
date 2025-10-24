<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\DeclarationId\AliasId;
use Typhoon\DeclarationId\AnonymousClassId;
use Typhoon\DeclarationId\ConstantId;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\DeclarationId\ParameterId;
use Typhoon\DeclarationId\TemplateId;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;
use Typhoon\Type\Variance;

/**
 * @implements TypeVisitor<mixed>
 */
final readonly class HydratingVisitor implements TypeVisitor
{
    public function __construct(public mixed $data)
    {
    }

    private function ifTypeOf(Type $type): mixed
    {
        return $type->accept(new IsValueTypeOf($this->data))
            ? $this->data
            : Error::create();
    }

    public function never(Type $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function void(Type $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function null(Type $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function true(Type $type): mixed
    {
        return $this->ifTypeOf($type);
    }


    public function false(Type $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function int(Type $type, Type $minType, Type $maxType): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function intValue(Type $type, int $value): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function intMask(Type $type, Type $ofType): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function float(Type $type, Type $minType, Type $maxType): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function floatValue(Type $type, float $value): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function string(Type $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function stringValue(Type $type, string $value): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function classString(Type $type, Type $classType): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function numeric(Type $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function literal(Type $type, Type $ofType): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function resource(Type $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function list(Type $type, Type $valueType, array $elements): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function array(Type $type, Type $keyType, Type $valueType, array $elements): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function key(Type $type, Type $arrayType): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function offset(Type $type, Type $arrayType, Type $keyType): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function iterable(Type $type, Type $keyType, Type $valueType): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function object(Type $type, array $properties): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function namedObject(Type $type, NamedClassId|AnonymousClassId $classId, array $typeArguments): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function self(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function parent(Type $type, array $typeArguments, ?NamedClassId $resolvedClassId): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function static(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function callable(Type $type, array $parameters, Type $returnType): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function constant(Type $type, ConstantId $constantId): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function classConstant(Type $type, Type $classType, string $name): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function classConstantMask(Type $type, Type $classType, string $namePrefix): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function alias(Type $type, AliasId $aliasId, array $typeArguments): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function template(Type $type, TemplateId $templateId): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function varianceAware(Type $type, Type $ofType, Variance $variance): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function union(Type $type, array $ofTypes): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function conditional(Type $type, Type $subjectType, Type $ifType, Type $thenType, Type $elseType): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function argument(Type $type, ParameterId $parameterId): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function intersection(Type $type, array $ofTypes): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function not(Type $type, Type $ofType): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function mixed(Type $type): mixed
    {
        return $this->ifTypeOf($type);
    }
}