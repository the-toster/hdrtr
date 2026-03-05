<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\Type;
use Typhoon\Type\ArrayBareT;
use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\BitmaskT;
use Typhoon\Type\BoolT;
use Typhoon\Type\CallableBareT;
use Typhoon\Type\CallableT;
use Typhoon\Type\ClassConstantMaskT;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ClassT;
use Typhoon\Type\ClosureT;
use Typhoon\Type\ConstantMaskT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\FloatT;
use Typhoon\Type\FloatValueT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\IterableBareT;
use Typhoon\Type\IterableT;
use Typhoon\Type\ListT;
use Typhoon\Type\LowercaseStringT;
use Typhoon\Type\MixedT;
use Typhoon\Type\NamedObjectT;
use Typhoon\Type\NegativeIntT;
use Typhoon\Type\NeverT;
use Typhoon\Type\NonEmptyStringT;
use Typhoon\Type\NonNegativeIntT;
use Typhoon\Type\NonPositiveIntT;
use Typhoon\Type\NonZeroIntT;
use Typhoon\Type\NullT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\NumericT;
use Typhoon\Type\ObjectShapeT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\PositiveIntT;
use Typhoon\Type\ResourceT;
use Typhoon\Type\ScalarT;
use Typhoon\Type\StringT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\TrueT;
use Typhoon\Type\TruthyStringT;
use Typhoon\Type\UnionT;
use Typhoon\Type\Visitor;
use Typhoon\Type\VoidT;

use function Typhoon\Type\arrayT;

/**
 * @implements Visitor<mixed>
 */
final readonly class HydratingVisitor implements Visitor
{
    /**
     * @param mixed $data
     * @param list<string> $path
     */
    public function __construct(
        public mixed $data,
        public array $path = [],
    ) {
    }

    public function failedToCast(Type $type, int|string|null $offset = null): Error
    {
        $path = $this->path;
        if ($offset !== null) {
            $path[] = (string) $offset;
        }

        return Error::failedToCast($type, $this->data, $path);
    }

    public function errorMissedKey(Type $type, string|null $offset): Error
    {
        $path = $this->path;
        if ($offset !== null) {
            $path[] = (string) $offset;
        }

        return Error::missedKey($type, $this->data, $path);
    }

    public function ifTypeOf(Type $type): mixed
    {
        return $type->accept(new IsSimpleValueTypeOf($this->data))
            ? $this->data
            : $this->failedToCast($type);
    }

    public function forOffset(string|int $name): self
    {
        if(!is_array($this->data)) {
            throw new \RuntimeException();
        }

        if(!array_key_exists($name, $this->data)) {
            throw new \RuntimeException();
        }

        return new self($this->data[$name], [...$this->path, $name]);
    }
     
    public function neverT(NeverT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function voidT(VoidT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function nullT(NullT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function falseT(FalseT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function trueT(TrueT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function boolT(BoolT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function intT(IntT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function intValueT(IntValueT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function intRangeT(IntRangeT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function negativeIntT(NegativeIntT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function nonPositiveIntT(NonPositiveIntT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function nonZeroIntT(NonZeroIntT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function nonNegativeIntT(NonNegativeIntT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function positiveIntT(PositiveIntT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function bitmaskT(BitmaskT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function floatT(FloatT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function floatValueT(FloatValueT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function floatRangeT(FloatRangeT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function stringT(StringT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function nonEmptyStringT(NonEmptyStringT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function truthyStringT(TruthyStringT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function numericStringT(NumericStringT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function lowercaseStringT(LowercaseStringT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function stringValueT(StringValueT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function classT(ClassT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function listT(ListT $type): mixed
    {
        if (!is_array($this->data)) {
            return $this->failedToCast($type);
        }

        $r = [];
        foreach ($this->data as $key => $value) {
            $itemResult = $type->valueType->accept($this->forOffset($key));
            if ($itemResult instanceof Error) {
                return $itemResult;
            }
            $r[] = $itemResult;
        }

        return $r;
    }

    public function arrayBareT(ArrayBareT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function arrayT(ArrayT $type): mixed
    {
        if (!is_array($this->data)) {
            return $this->failedToCast($type);
        }

        $r = [];
        foreach ($type->elements as $element) {
            if (!array_key_exists($element->key, $this->data)) {
                if($element->isOptional) {
                    continue;
                }

                return $this->errorMissedKey($type, $element->key);
            }

            $elementResult = $element->type->accept($this->forOffset($element->key));

            if($elementResult instanceof Error) {
                return $elementResult;
            }

            $r[$element->key] = $elementResult;
        }

        if(
            $type->valueType->accept(new IsNever())
            && $type->keyType->accept(new IsNever())
        ) {
            // sealed
            return $r;
        }

        foreach ($this->data as $key => $value) {
            if(array_key_exists($key, $r)) {
                continue;
            }

            $keyResult = $type->keyType->accept(new self($key, [...$this->path, 'keyOf(' . $key . ')']));
            if ($keyResult instanceof Error) {
                return $keyResult;
            }

            $itemResult = $type->valueType->accept($this->forOffset($key));
            if ($itemResult instanceof Error) {
                return $itemResult;
            }
            $r[$key] = $itemResult;
        }

        if($type->isNonEmpty && count($r) === 0) {
            return Error::shouldBeNonEmpty($type, $this->data, $this->path);
        }


        return $r;
    }

    public function objectT(ObjectT $type): mixed
    {
        if (is_object($this->data)) {
            return $this->data;
        }

        if (!is_iterable($this->data)) {
            return $this->failedToCast($type);
        }

        $r = new \stdClass();
        foreach ($this->data as $key => $value) {
            $r->{$key} = $value;
        }

        return $r;
    }

    public function namedObjectT(NamedObjectT $type): mixed
    {
        if ($type->accept(new IsSimpleValueTypeOf($this->data))) {
            return $this->data;
        }

        if (!is_array($this->data)) {
            return $this->failedToCast($type);
        }

        return (new ObjectInstantiator)->buildInstance($type, $this->data, $this);
    }

    public function objectShapeT(ObjectShapeT $type): mixed
    {
        if (!is_array($this->data)) {
            return $this->failedToCast($type);
        }

        $r = new \stdClass();
        foreach ($type->properties as $property) {
            $hasElement = array_key_exists($property->name, $this->data);
            if (!$hasElement && !$property->isOptional) {
                return $this->errorMissedKey($type, $property->name);
            }

            $propertyResult = $property->type->accept($this->forOffset($property->name));
            if ($propertyResult instanceof Error) {
                return $propertyResult;
            }
            $r->{$property->name} = $propertyResult;
        }

        return $r;
    }

    public function iterableBareT(IterableBareT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function iterableT(IterableT $type): mixed
    {
        /** @phpstan-ignore argument.templateType */
        return arrayT(key: $type->keyType, value: $type->valueType)->accept($this);
    }

    public function callableBareT(CallableBareT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function callableT(CallableT $type): mixed
    {
        throw new Unimplemented();
    }

    public function closureT(ClosureT $type): mixed
    {
        throw new Unimplemented();
    }

    public function resourceT(ResourceT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function constantT(ConstantT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function constantMaskT(ConstantMaskT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function classConstantT(ClassConstantT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function classConstantMaskT(ClassConstantMaskT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function intersectionT(IntersectionT $type): mixed
    {
        throw new Unimplemented();
    }

    public function unionT(UnionT $type): mixed
    {
        foreach ($type->types as $elementType) {
            $r = $elementType->accept($this);
            if ($r instanceof Error) {
                continue;
            }
            return $r;
        }
        return $r;
    }

    public function arrayKeyT(ArrayKeyT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function numericT(NumericT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function scalarT(ScalarT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

    public function mixedT(MixedT $type): mixed
    {
        return $this->ifTypeOf($type);
    }

}