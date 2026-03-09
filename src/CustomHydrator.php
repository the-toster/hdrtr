<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\Type;

/**
 * @template T
 */
interface CustomHydrator
{
    /**
     * @param  Type<T>  $type
     * @param  list<string> $path
     * @return T|Error
     */
    public function hydrate(mixed $data, Type $type, array $path, Hydrator $hydrator): mixed;

    /**
     * @param  Type<T>  $type
     */
    public function supports(mixed $data, Type $type): bool;
}