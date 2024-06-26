<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;

/**
 * @extends TypeVisitor<\Closure(HydratorSelector $hydrator): mixed>
 */
interface TypeHydrator extends TypeVisitor
{
    public function supports(Type $type): bool;
}