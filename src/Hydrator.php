<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\Type;

final readonly class Hydrator
{
    /**
     * @param list<CustomHydrator> $customHydrators
     */
    public function __construct(
        private array $customHydrators = [],
    )
    {
    }

    /**
     * @template T
     * @param  Type<T>  $type
     * @param  list<string> $path
     * @return T|Error
     */
    public function hydrate(mixed $data, Type $type, array $path = []): mixed
    {
        foreach ($this->customHydrators as $customHydrator) {
            if ($customHydrator->supports($data, $type)) {
                return $customHydrator->hydrate($data, $type, $path, $this);
            }
        }

        return $type->accept(new HydratingVisitor($data, $this, $path));
    }
}