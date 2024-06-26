<?php

declare(strict_types=1);

namespace Typhoon\TypeComparator;

use Typhoon\Type\DefaultTypeVisitor;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;

/**
 * @internal
 * @psalm-internal Typhoon\TypeComparator
 * @extends DefaultTypeVisitor<TypeVisitor<bool>>
 */
final class ComparatorSelector extends DefaultTypeVisitor
{
    public function array(Type $self, Type $key, Type $value, array $elements): mixed
    {
        return new IsArray($key, $value, $elements);
    }

    public function bool(Type $self): mixed
    {
        return new IsBool();
    }

    public function float(Type $self): mixed
    {
        return new IsFloat();
    }

    public function intersection(Type $self, array $types): mixed
    {
        return new IsIntersection($types);
    }

    public function int(Type $self, ?int $min = null, ?int $max = null): mixed
    {
        return new IsInt($min, $max);
    }

    public function literal(Type $self, Type $type): mixed
    {
        return new IsLiteral($type);
    }

    public function literalValue(Type $self, float|bool|int|string $value): mixed
    {
        return new IsLiteralValue($value);
    }

    public function list(Type $self, Type $value, array $elements): mixed
    {
        return new IsList($value, $elements);
    }

    public function mixed(Type $self): mixed
    {
        return new IsMixed();
    }

    public function namedObject(Type $self, string $class, array $arguments): mixed
    {
        return new IsNamedObject($class);
    }

    public function never(Type $self): mixed
    {
        return new IsNever();
    }

    public function nonEmpty(Type $self, Type $type): mixed
    {
        return new IsNonEmpty($type);
    }

    public function null(Type $self): mixed
    {
        return new IsNull();
    }

    public function numericString(Type $self): mixed
    {
        return new IsNumericString();
    }

    public function object(Type $self): mixed
    {
        return new IsObject();
    }

    public function resource(Type $self): mixed
    {
        return new IsResource();
    }

    public function string(Type $self): mixed
    {
        return new IsString();
    }

    public function truthyString(Type $self): mixed
    {
        return new IsTruthyString();
    }

    public function union(Type $self, array $types): mixed
    {
        return new IsUnion($types);
    }

    public function void(Type $self): mixed
    {
        return new IsVoid();
    }

    protected function default(Type $self): mixed
    {
        return new /** @extends DefaultTypeVisitor<bool> */ class () extends DefaultTypeVisitor {
            protected function default(Type $self): bool
            {
                return false;
            }
        };
    }
}
