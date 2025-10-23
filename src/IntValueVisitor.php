<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\DeclarationId\ConstantId;
use Typhoon\Type\Type;
use Typhoon\Type\Visitor\DefaultTypeVisitor;

/**
 * @extends DefaultTypeVisitor<int>
 */
final class IntValueVisitor extends DefaultTypeVisitor
{
    public function intValue(Type $type, int $value): int
    {
        return $value;
    }

    public function constant(Type $type, ConstantId $constantId): int
    {
        if(!defined($constantId->name)) {
            return $this->default();
        }
        $v = constant($constantId->name);

        return is_int($v)
            ? $v
            : $this->default($type);
    }


    protected function default(Type $type): never
    {
        throw new \RuntimeException('non int type');
    }
}