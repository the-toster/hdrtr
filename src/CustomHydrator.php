<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\Type;

interface CustomHydrator
{
    /**
     * @template T
     * @param  Type<T>  $type
     * @param  list<string> $path
     * @return T|Error
     */
    public function hydrate(mixed $data, Type $type, array $path, Hydrator $hydrator): mixed;
    public function supports(mixed $data, Type $type): bool;
}