<?php

declare(strict_types=1);

namespace Hdrtr;

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
final readonly class IsNever implements Visitor
{
    public function neverT(NeverT $type): bool
    {
        return true;
    }

    public function voidT(VoidT $type): bool
    {
        return false;
    }

    public function nullT(NullT $type): bool
    {
        return false;
    }

    public function falseT(FalseT $type): bool
    {
        return false;
    }

    public function trueT(TrueT $type): bool
    {
        return false;
    }

    public function boolT(BoolT $type): bool
    {
        return false;
    }

    public function intT(IntT $type): bool
    {
        return false;
    }

    public function intValueT(IntValueT $type): bool
    {
        return false;
    }

    public function intRangeT(IntRangeT $type): bool
    {
        return false;
    }

    public function negativeIntT(NegativeIntT $type): bool
    {
        return false;
    }

    public function nonPositiveIntT(NonPositiveIntT $type): bool
    {
        return false;
    }

    public function nonZeroIntT(NonZeroIntT $type): bool
    {
        return false;
    }

    public function nonNegativeIntT(NonNegativeIntT $type): bool
    {
        return false;
    }

    public function positiveIntT(PositiveIntT $type): bool
    {
        return false;
    }

    public function bitmaskT(BitmaskT $type): bool
    {
        return false;
    }

    public function floatT(FloatT $type): bool
    {
        return false;
    }

    public function floatValueT(FloatValueT $type): bool
    {
        return false;
    }

    public function floatRangeT(FloatRangeT $type): bool
    {
        return false;
    }

    public function stringT(StringT $type): bool
    {
        return false;
    }

    public function nonEmptyStringT(NonEmptyStringT $type): bool
    {
        return false;
    }

    public function truthyStringT(TruthyStringT $type): bool
    {
        return false;
    }

    public function numericStringT(NumericStringT $type): bool
    {
        return false;
    }

    public function lowercaseStringT(LowercaseStringT $type): bool
    {
        return false;
    }

    public function stringValueT(StringValueT $type): bool
    {
        return false;
    }

    public function classT(ClassT $type): bool
    {
        return false;
    }

    public function listT(ListT $type): bool
    {
        return false;
    }

    public function arrayBareT(ArrayBareT $type): bool
    {
        return false;
    }

    public function arrayT(ArrayT $type): bool
    {
        return false;
    }

    public function objectT(ObjectT $type): bool
    {
        return false;
    }

    public function namedObjectT(NamedObjectT $type): bool
    {
        return false;
    }

    public function objectShapeT(ObjectShapeT $type): bool
    {
        return false;
    }

    public function iterableBareT(IterableBareT $type): bool
    {
        return false;
    }

    public function iterableT(IterableT $type): bool
    {
        return false;
    }

    public function callableBareT(CallableBareT $type): bool
    {
        return false;
    }

    public function callableT(CallableT $type): bool
    {
        return false;
    }

    public function closureT(ClosureT $type): bool
    {
        return false;
    }

    public function resourceT(ResourceT $type): bool
    {
        return false;
    }

    public function constantT(ConstantT $type): bool
    {
        return false;
    }

    public function constantMaskT(ConstantMaskT $type): bool
    {
        return false;
    }

    public function classConstantT(ClassConstantT $type): bool
    {
        return false;
    }

    public function classConstantMaskT(ClassConstantMaskT $type): bool
    {
        return false;
    }

    public function intersectionT(IntersectionT $type): bool
    {
        foreach ($type->types as $part) {
            if ($part->accept($this)) {
                return true;
            }
        }

        return false;
    }

    public function unionT(UnionT $type): bool
    {
        foreach ($type->types as $part) {
            if (!$part->accept($this)) {
                return false;
            }
        }

        return true;
    }

    public function arrayKeyT(ArrayKeyT $type): bool
    {
        return false;
    }

    public function numericT(NumericT $type): bool
    {
        return false;
    }

    public function scalarT(ScalarT $type): bool
    {
        return false;
    }

    public function mixedT(MixedT $type): bool
    {
        return false;
    }
}
