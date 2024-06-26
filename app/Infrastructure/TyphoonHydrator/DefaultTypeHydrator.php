<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\DefaultTypeVisitor;
use Typhoon\Type\Type;

/**
 * @extends DefaultTypeVisitor<\Closure(HydratorSelector):(mixed|Error)>
 */
abstract class DefaultTypeHydrator extends DefaultTypeVisitor implements TypeHydrator
{
    protected function default(Type $self): mixed
    {
        return fn(HydratorSelector $hydratorSelector) => $hydratorSelector->unsupportedType($self);
    }

}