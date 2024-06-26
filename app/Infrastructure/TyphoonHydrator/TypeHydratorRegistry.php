<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\Type;

final readonly class TypeHydratorRegistry
{
    /**
     * @param  list<TypeHydrator>  $hydrators
     */
    public function __construct(
        private array $hydrators = [
            new UuidH(),
            new NullH(),
            new BoolH(),
            new IntH(),
            new FloatH(),
            new StringH(),
            new ObjectH(),
            new ArrayH(),
            new ListH(),
            new MixedH(),
        ]
    ) {
    }

    public function find(Type $type): ?TypeHydrator
    {
        foreach ($this->hydrators as $hydrator) {
            if ($hydrator->supports($type)) {
                return $hydrator;
            }
        }
        return null;
    }
}