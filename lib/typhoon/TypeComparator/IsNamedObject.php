<?php

declare(strict_types=1);

namespace Typhoon\TypeComparator;

use Typhoon\Type\Type;

final class IsNamedObject extends Comparator
{
    public function __construct(private readonly string $class)
    {
    }

    public function namedObject(Type $self, string $class, array $arguments): bool
    {
        return is_a($class, $this->class, allow_string: true);
    }
}