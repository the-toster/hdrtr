<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\DeclarationId\ConstantId;
use Typhoon\Type\Type;
use Typhoon\Type\Visitor\DefaultTypeVisitor;

/**
 * @extends DefaultTypeVisitor<?scalar>
 */
final class FindScalarValue extends DefaultTypeVisitor
{

    public function true(Type $type): true
    {
        return true;
    }

    public function false(Type $type): false
    {
        return false;
    }

    public function intValue(Type $type, int $value): int
    {
        return $value;
    }

    public function floatValue(Type $type, float $value): float
    {
        return $value;
    }

    public function stringValue(Type $type, string $value): string
    {
        return $value;
    }

    public function literal(Type $type, Type $ofType): mixed
    {
        return $ofType->accept($this);
    }

    public function constant(Type $type, ConstantId $constantId): ?int
    {
        if (!defined($constantId->name)) {
            return null;
        }
        $v = constant($constantId->name);

        return is_scalar($v) ? $v : null;
    }


    protected function default(Type $type): null
    {
        return null;
    }
}