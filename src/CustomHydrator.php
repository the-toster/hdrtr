<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\Type;

interface CustomHydrator
{
    /**
     * @param  list<string> $path
     */
    public function hydrate(mixed $data, Type $type, array $path, Hydrator $hydrator): mixed;

    public function supports(mixed $data, Type $type): bool;
}