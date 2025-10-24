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
final readonly class IsValueTypeOf implements TypeVisitor
{
    public function __construct(public mixed $data)
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
        return is_null($this->data);
    }

    public function true(Type $type): mixed
    {
        return $this->data === true;
    }

    public function false(Type $type): mixed
    {
        return $this->data === false;
    }

    public function int(Type $type, Type $minType, Type $maxType): mixed
    {
        if (!is_int($this->data)) {
            return false;
        }
        $max = $maxType->accept(new FindScalarValue());
        $min = $minType->accept(new FindScalarValue());
        return is_int($min)
            && is_int($max)
            && $this->data <= $max
            && $this->data >= $min;
    }

    public function intValue(Type $type, int $value): mixed
    {
        return $this->data === $value;
    }

    public function intMask(Type $type, Type $ofType): mixed
    {
        return $ofType->accept($this);
    }

    public function float(Type $type, Type $minType, Type $maxType): mixed
    {
        if (!is_float($this->data)) {
            return false;
        }

        $max = $maxType->accept(new FindScalarValue());
        $min = $minType->accept(new FindScalarValue());

        return is_float($max)
            && is_float($min)
            && $this->data <= $max
            && $this->data >= $min;
    }

    public function floatValue(Type $type, float $value): mixed
    {
        return $this->data === $value;
    }

    public function string(Type $type): mixed
    {
        return is_string($this->data);
    }

    public function stringValue(Type $type, string $value): mixed
    {
        return $this->data === $value;
    }

    public function classString(Type $type, Type $classType): mixed
    {
        if(!is_string($this->data)) {
            return false;
        }

        $name = $classType->accept(new FindClassName());

        if(!is_string($this->data)) {
            return false;
        }
    }

    public function numeric(Type $type): mixed
    {
        return is_numeric($this->data);
    }

    public function literal(Type $type, Type $ofType): mixed
    {
        return $ofType->accept($this);
    }

    public function resource(Type $type): mixed
    {
        return is_resource($this->data);
    }

    public function list(Type $type, Type $valueType, array $elements): mixed
    {
        if (
            !is_array($this->data)
            || !array_is_list($this->data)
        ) {
            return false;
        }

        foreach ($this->data as $item) {
            if (!$valueType->accept(new self($item))) {
                return false;
            }
        }


        foreach ($elements as $index => $element) {
            if (!array_key_exists($index, $this->data)) {
                if ($element->optional) {
                    continue;
                } else {
                    return false;
                }
            }

            if (!$element->type->accept(new self($this->data[$index]))) {
                return false;
            }
        }

        return true;
    }

    public function array(Type $type, Type $keyType, Type $valueType, array $elements): mixed
    {
        if (!is_array($this->data)) {
            return false;
        }

        foreach ($this->data as $k => $v) {
            if (
                $keyType->accept(new self($k))
                && $valueType->accept(new self($v))
            ) {
                continue;
            }

            return false;
        }

        foreach ($elements as $index => $element) {
            if (!array_key_exists($index, $this->data)) {
                if ($element->optional) {
                    continue;
                }
                return false;
            }

            if (!$element->type->accept(new self($this->data[$index]))) {
                return false;
            }
        }

        return true;
    }

    public function key(Type $type, Type $arrayType): mixed
    {
        return is_scalar($this->data);
    }

    public function offset(Type $type, Type $arrayType, Type $keyType): mixed
    {
        return is_scalar($this->data);
    }

    public function iterable(Type $type, Type $keyType, Type $valueType): mixed
    {
        return is_iterable($this->data);
    }

    public function object(Type $type, array $properties): mixed
    {
        return is_object($this->data);
    }

    public function namedObject(Type $type, NamedClassId|AnonymousClassId $classId, array $typeArguments): mixed
    {
        return is_object($this->data) && $this->data::class === $classId->name;
    }

    public function self(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): mixed
    {
        return false;
    }

    public function parent(Type $type, array $typeArguments, ?NamedClassId $resolvedClassId): mixed
    {
        return false;
    }

    public function static(Type $type, array $typeArguments, NamedClassId|AnonymousClassId|null $resolvedClassId): mixed
    {
        return false;
    }

    public function callable(Type $type, array $parameters, Type $returnType): mixed
    {
        return is_callable($this->data);
    }

    public function constant(Type $type, ConstantId $constantId): mixed
    {
        if (!defined($constantId->name)) {
            return false;
        }

        return $this->data === constant($constantId->name);
    }

    public function classConstant(Type $type, Type $classType, string $name): mixed
    {
        // TODO: Implement classConstant() method.
        return false;
    }

    public function classConstantMask(Type $type, Type $classType, string $namePrefix): mixed
    {
        // TODO: Implement classConstantMask() method.
        return false;
    }

    public function alias(Type $type, AliasId $aliasId, array $typeArguments): mixed
    {
        return false;
    }

    public function template(Type $type, TemplateId $templateId): mixed
    {
        return false;
    }

    public function varianceAware(Type $type, Type $ofType, Variance $variance): mixed
    {
        return false;
    }

    public function union(Type $type, array $ofTypes): mixed
    {
        foreach ($ofTypes as $ofType) {
            if ($ofType->accept($this)) {
                return true;
            }
        }

        return false;
    }

    public function conditional(Type $type, Type $subjectType, Type $ifType, Type $thenType, Type $elseType): mixed
    {
        return false;
    }

    public function argument(Type $type, ParameterId $parameterId): mixed
    {
        return true;
    }

    public function intersection(Type $type, array $ofTypes): mixed
    {
        foreach ($ofTypes as $ofType) {
            if (!$ofType->accept($this)) {
                return false;
            }
        }

        return true;
    }

    public function not(Type $type, Type $ofType): mixed
    {
        return !$ofType->accept($this);
    }

    public function mixed(Type $type): mixed
    {
        return true;
    }
}