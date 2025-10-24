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
 * @implements TypeVisitor<bool>
 */
final readonly class IsClassInstanceOf implements TypeVisitor
{

    public function __construct(public NamedClassId $classId)
    {
    }

    public function never(Type $type): mixed
    {
        return false;
    }

    public function void(Type $type): mixed
    {
        return false;
    }

    public function null(Type $type): mixed
    {
        return false;
    }

    public function true(Type $type): mixed
    {
        return false;
    }

    public function false(Type $type): mixed
    {
        return false;
    }

    public function int(Type $type, Type $minType, Type $maxType): mixed
    {
        return false;
    }

    public function intValue(Type $type, int $value): mixed
    {
        return false;
    }

    public function intMask(Type $type, Type $ofType): mixed
    {
        return false;
    }

    public function float(Type $type, Type $minType, Type $maxType): mixed
    {
        return false;
    }

    public function floatValue(Type $type, float $value): mixed
    {
        return false;
    }

    public function string(Type $type): mixed
    {
        return false;
    }

    public function stringValue(Type $type, string $value): mixed
    {
        return false;
    }

    public function classString(Type $type, Type $classType): mixed
    {
        return false;
    }

    public function numeric(Type $type): mixed
    {
        return false;
    }

    public function literal(Type $type, Type $ofType): mixed
    {
        return false;
    }

    public function resource(Type $type): mixed
    {
        return false;
    }

    public function list(Type $type, Type $valueType, array $elements): mixed
    {
        return false;
    }

    public function array(Type $type, Type $keyType, Type $valueType, array $elements): mixed
    {
        return false;
    }

    public function key(Type $type, Type $arrayType): mixed
    {
        return false;
    }

    public function offset(Type $type, Type $arrayType, Type $keyType): mixed
    {
        return false;
    }

    public function iterable(Type $type, Type $keyType, Type $valueType): mixed
    {
        return false;
    }

    public function object(Type $type, array $properties): mixed
    {
        $reflection = $this->classId->reflect();
        foreach ($properties as $name => $typeProperty) {
            $property = $reflection->hasProperty($name)
                ? $reflection->getProperty($name)
                : null;

            if ($property === null) {
                if($typeProperty->optional) {
                    continue;
                } else {
                    return false;
                }
            }


        }
    }

    public function namedObject(Type $type, NamedClassId|AnonymousClassId $classId, array $typeArguments): mixed
    {
        // TODO: Implement namedObject() method.
    }

    public function self(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): mixed
    {
        // TODO: Implement self() method.
    }

    public function parent(Type $type, array $typeArguments, ?NamedClassId $resolvedClassId): mixed
    {
        // TODO: Implement parent() method.
    }

    public function static(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): mixed
    {
        // TODO: Implement static() method.
    }

    public function callable(Type $type, array $parameters, Type $returnType): mixed
    {
        // TODO: Implement callable() method.
    }

    public function constant(Type $type, ConstantId $constantId): mixed
    {
        // TODO: Implement constant() method.
    }

    public function classConstant(Type $type, Type $classType, string $name): mixed
    {
        // TODO: Implement classConstant() method.
    }

    public function classConstantMask(Type $type, Type $classType, string $namePrefix): mixed
    {
        // TODO: Implement classConstantMask() method.
    }

    public function alias(Type $type, AliasId $aliasId, array $typeArguments): mixed
    {
        // TODO: Implement alias() method.
    }

    public function template(Type $type, TemplateId $templateId): mixed
    {
        // TODO: Implement template() method.
    }

    public function varianceAware(Type $type, Type $ofType, Variance $variance): mixed
    {
        // TODO: Implement varianceAware() method.
    }

    public function union(Type $type, array $ofTypes): mixed
    {
        // TODO: Implement union() method.
    }

    public function conditional(Type $type, Type $subjectType, Type $ifType, Type $thenType, Type $elseType): mixed
    {
        // TODO: Implement conditional() method.
    }

    public function argument(Type $type, ParameterId $parameterId): mixed
    {
        // TODO: Implement argument() method.
    }

    public function intersection(Type $type, array $ofTypes): mixed
    {
        // TODO: Implement intersection() method.
    }

    public function not(Type $type, Type $ofType): mixed
    {
        // TODO: Implement not() method.
    }

    public function mixed(Type $type): mixed
    {
        return true;
    }
}