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
 * @implements Visitor<mixed>
 */
final readonly class HydratingVisitor implements Visitor
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

    public function neverT(NeverT $type): mixed
    {
        return Error::create();
    }

    public function voidT(VoidT $type): mixed
    {
        return Error::create();
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
        // TODO: Implement intersectionT() method.
    }

    public function unionT(UnionT $type): mixed
    {
        // TODO: Implement unionT() method.
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