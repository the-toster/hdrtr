<?php

declare(strict_types=1);

namespace Typhoon\TypeComparator;

use Typhoon\Type\Type;

/**
 * @internal
 * @psalm-internal Typhoon\TypeComparator
 */
final class IsList extends Comparator
{
    /**
     * @param  array<int,Type>  $elements
     */
    public function __construct(
        private readonly Type $itemType,
        private readonly array $elements,
    ) {
    }

    /**
     * @param  array<int,Type>  $elements
     * @return mixed
     */
    public function list(Type $self, Type $value, array $elements): mixed
    {
        if (count($this->elements) > 0) {
            return $this->isShapeSuperOf($elements);
        }

        return isSubtype($self, $this->itemType);
    }

    private function isShapeSuperOf(array $elements): bool
    {
        foreach ($this->elements as $index => $element) {
            if (!isset($elements[$index])) {
                return false;
            }

            if (!isSubtype($elements[$index], of: $element)) {
                return false;
            }
        }

        return true;
    }
}
