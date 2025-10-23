<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\DeclarationId\ConstantId;
use Typhoon\Type\Type;
use Typhoon\Type\Visitor\DefaultTypeVisitor;

/**
 * @extends DefaultTypeVisitor<int>
 */
final class FloatValueVisitor extends DefaultTypeVisitor
{
    public function floatValue(Type $type, float $value): float
    {
        return $value;
    }

    public function constant(Type $type, ConstantId $constantId): float
    {
        $v = constant($constantId->name);

        return is_float($v)
            ? $v
            : $this->default($type);
    }


    protected function default(Type $type): never
    {
        throw new \RuntimeException('non int type');
    }
}