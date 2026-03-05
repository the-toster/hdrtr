<?php

declare(strict_types=1);

namespace Hdrtr;

use ReflectionClass;
use ReflectionFunction;
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
final readonly class IsSimpleValueTypeOf implements Visitor
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
        return is_null($this->data);
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
        $this->unimplemented();
    }

    public function floatT(FloatT $type): mixed
    {
        return is_int($this->data) || is_float($this->data);
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
        return is_string($this->data) && (bool) $this->data;
    }

    public function numericStringT(NumericStringT $type): mixed
    {
        return is_string($this->data) && is_numeric($this->data);
    }

    public function lowercaseStringT(LowercaseStringT $type): mixed
    {
        return is_string($this->data)
            && (mb_strtolower($this->data) === $this->data);
    }

    public function stringValueT(StringValueT $type): mixed
    {
        return $this->data === $type->value;
    }

    public function classT(ClassT $type): mixed
    {
        return is_string($this->data) &&
            preg_match('~^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*~', $this->data) === 1;
    }

    public function listT(ListT $type): mixed
    {
        return is_array($this->data) && array_is_list($this->data);
    }

    public function arrayBareT(ArrayBareT $type): mixed
    {
        return is_array($this->data);
    }

    public function arrayT(ArrayT $type): mixed
    {
        return false;
    }

    public function objectT(ObjectT $type): mixed
    {
        return is_object($this->data);
    }

    public function namedObjectT(NamedObjectT $type): mixed
    {
        return is_object($this->data)
            && is_a($this->data, $type->class);
    }

    public function objectShapeT(ObjectShapeT $type): mixed
    {
        return false;
    }

    public function iterableBareT(IterableBareT $type): mixed
    {
        return is_iterable($this->data);
    }

    public function iterableT(IterableT $type): mixed
    {
        return false;
    }

    public function callableBareT(CallableBareT $type): mixed
    {
        return is_callable($this->data);
    }

    public function callableT(CallableT $type): mixed
    {
        return false;
    }

    public function closureT(ClosureT $type): mixed
    {
        return false;
    }

    public function resourceT(ResourceT $type): mixed
    {
        return is_resource($this->data);
    }

    public function constantT(ConstantT $type): mixed
    {
        return
            defined($type->name)
            && $this->data === constant($type->name);
    }

    public function constantMaskT(ConstantMaskT $type): mixed
    {
        foreach (get_defined_constants() as $name => $value) {
            if ($this->data === $value
                && $type->mask->test($name)
            ) {
                return true;
            }
        };

        return false;
    }

    public function classConstantT(ClassConstantT $type): mixed
    {
        return
            class_exists($type->class)
            && defined($type->class . '::' . $type->name)
            && constant($type->class . '::' . $type->name) === $this->data;
    }

    public function classConstantMaskT(ClassConstantMaskT $type): mixed
    {
        if (!class_exists($type->class)) {
            return false;
        }

        $reflection = new ReflectionClass($type->class);

        foreach ($reflection->getConstants() as $name => $value) {
            if ($this->data === $value && $type->mask->test($name)) {
                return true;
            }
        }

        return false;
    }

    public function intersectionT(IntersectionT $type): mixed
    {
        foreach ($type->types as $part) {
            if (!$part->accept($this)) {
                return false;
            }
        }

        return true;
    }

    public function unionT(UnionT $type): mixed
    {
        foreach ($type->types as $part) {
            if ($part->accept($this)) {
                return true;
            }
        }

        return false;
    }

    public function arrayKeyT(ArrayKeyT $type): mixed
    {
        return
            (is_string($this->data) && preg_match('~^\d+$~', $this->data) === 0)
            || is_int($this->data);
    }

    public function numericT(NumericT $type): mixed
    {
        return is_numeric($this->data);
    }

    public function scalarT(ScalarT $type): mixed
    {
        return is_scalar($this->data);
    }

    public function mixedT(MixedT $type): mixed
    {
        return true;
    }

    private function unimplemented(): never
    {
        throw new Unimplemented();
    }
}