<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\Type;

final readonly class Hydrator
{
    /**
     * @template T
     * @param  Type<T>  $type
     * @return T|Error
     */
    public function hydrate(mixed $data, Type $type): mixed
    {
        return $type->accept(new HydratingVisitor($data));
    }
}