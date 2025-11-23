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

/**
 * @implements Visitor<bool>
 */
final readonly class IsValueTypeOf implements Visitor
{
    public function __construct(public mixed $data)
    {
    }

    public function neverT(NeverT $type): mixed
    {
        return false;
    }

    public function voidT(VoidT $type): mixed
    {
        return false;
    }

    public function nullT(NullT $type): mixed
    {
        return is_null($type);
    }

    public function falseT(FalseT $type): mixed
    {
        return $this->data === false;
    }

    public function trueT(TrueT $type): mixed
    {
        return $this->data === true;
    }

    public function boolT(BoolT $type): mixed
    {
        return is_bool($this->data);
    }

    public function intT(IntT $type): mixed
    {
        return is_int($this->data);
    }

    public function intValueT(IntValueT $type): mixed
    {
        return $this->data === $type->value;
    }

    public function intRangeT(IntRangeT $type): mixed
    {
        return is_int($this->data)
            && ($type->max === null || $this->data <= $type->max)
            && ($type->min === null || $this->data >= $type->min);
    }

    public function negativeIntT(NegativeIntT $type): mixed
    {
        return is_int($this->data) && $this->data < 0;
    }

    public function nonPositiveIntT(NonPositiveIntT $type): mixed
    {
        return is_int($this->data) && $this->data <= 0;
    }

    public function nonZeroIntT(NonZeroIntT $type): mixed
    {
        return is_int($this->data) && $this->data !== 0;
    }

    public function nonNegativeIntT(NonNegativeIntT $type): mixed
    {
        return is_int($this->data) && $this->data >= 0;
    }

    public function positiveIntT(PositiveIntT $type): mixed
    {
        return is_int($this->data) && $this->data > 0;
    }

    public function bitmaskT(BitmaskT $type): mixed
    {
        // TODO: Implement bitmaskT() method.
    }

    public function floatT(FloatT $type): mixed
    {
        return is_float($this->data);
    }

    public function floatValueT(FloatValueT $type): mixed
    {
        return $this->data === $type->value;
    }

    public function floatRangeT(FloatRangeT $type): mixed
    {
        return is_float($this->data)
            && ($type->max === null || $this->data <= $type->max)
            && ($type->min === null || $this->data >= $type->min);
    }

    public function stringT(StringT $type): mixed
    {
        return is_string($this->data);
    }

    public function nonEmptyStringT(NonEmptyStringT $type): mixed
    {
        return is_string($this->data) && $this->data !== '';
    }

    public function truthyStringT(TruthyStringT $type): mixed
    {
        return is_string($this->data) && (bool)$this->data;
    }

    public function numericStringT(NumericStringT $type): mixed
    {
        // TODO: Implement numericStringT() method.
    }

    public function lowercaseStringT(LowercaseStringT $type): mixed
    {
        // TODO: Implement lowercaseStringT() method.
    }

    public function stringValueT(StringValueT $type): mixed
    {
        // TODO: Implement stringValueT() method.
    }

    public function classT(ClassT $type): mixed
    {
        // TODO: Implement classT() method.
    }

    public function listT(ListT $type): mixed
    {
        // TODO: Implement listT() method.
    }

    public function arrayBareT(ArrayBareT $type): mixed
    {
        // TODO: Implement arrayBareT() method.
    }

    public function arrayT(ArrayT $type): mixed
    {
        // TODO: Implement arrayT() method.
    }

    public function objectT(ObjectT $type): mixed
    {
        // TODO: Implement objectT() method.
    }

    public function namedObjectT(NamedObjectT $type): mixed
    {
        // TODO: Implement namedObjectT() method.
    }

    public function objectShapeT(ObjectShapeT $type): mixed
    {
        // TODO: Implement objectShapeT() method.
    }

    public function iterableBareT(IterableBareT $type): mixed
    {
        // TODO: Implement iterableBareT() method.
    }

    public function iterableT(IterableT $type): mixed
    {
        // TODO: Implement iterableT() method.
    }

    public function callableBareT(CallableBareT $type): mixed
    {
        // TODO: Implement callableBareT() method.
    }

    public function callableT(CallableT $type): mixed
    {
        // TODO: Implement callableT() method.
    }

    public function closureT(ClosureT $type): mixed
    {
        // TODO: Implement closureT() method.
    }

    public function resourceT(ResourceT $type): mixed
    {
        // TODO: Implement resourceT() method.
    }

    public function constantT(ConstantT $type): mixed
    {
        // TODO: Implement constantT() method.
    }

    public function constantMaskT(ConstantMaskT $type): mixed
    {
        // TODO: Implement constantMaskT() method.
    }

    public function classConstantT(ClassConstantT $type): mixed
    {
        // TODO: Implement classConstantT() method.
    }

    public function classConstantMaskT(ClassConstantMaskT $type): mixed
    {
        // TODO: Implement classConstantMaskT() method.
    }

    public function intersectionT(IntersectionT $type): mixed
    {
        // TODO: Implement intersectionT() method.
    }

    public function unionT(UnionT $type): mixed
    {
        // TODO: Implement unionT() method.
    }

    public function arrayKeyT(ArrayKeyT $type): mixed
    {
        // TODO: Implement arrayKeyT() method.
    }

    public function numericT(NumericT $type): mixed
    {
        // TODO: Implement numericT() method.
    }

    public function scalarT(ScalarT $type): mixed
    {
        // TODO: Implement scalarT() method.
    }

    public function mixedT(MixedT $type): mixed
    {
        // TODO: Implement mixedT() method.
    }
}