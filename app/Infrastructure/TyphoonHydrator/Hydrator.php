<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\Type;

final readonly class Hydrator
{
    public function __construct(
        private TypeHydratorRegistry $hydratorRegistry = new TypeHydratorRegistry()
    ) {
    }

    /**
     * @template T
     * @param  Type<T>  $type
     * @return T|Error
     */
    public function hydrate(mixed $data, Type $type): mixed
    {
        $visitor = new HydratorSelector($this->hydratorRegistry, $data, []);
        /** @var T|Error */
        return $type->accept($visitor);
    }
}