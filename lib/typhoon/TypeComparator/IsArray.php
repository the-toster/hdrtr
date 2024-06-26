<?php

declare(strict_types=1);

namespace Typhoon\TypeComparator;

use Typhoon\Type\ArrayElement;
use Typhoon\Type\Type;

/**
 * @internal
 * @psalm-internal Typhoon\TypeComparator
 */
final class IsArray extends Comparator
{
    /**
     * @param  array<ArrayElement>  $elements
     */
    public function __construct(
        private readonly Type $keyType,
        private readonly Type $itemType,
        private readonly array $elements,
    ) {
    }

    /**
     * @param  array<ArrayElement>  $elements
     */
    public function array(Type $self, Type $key, Type $value, array $elements): mixed
    {
        if (count($this->elements) > 0) {
            return $this->isShapeSuperOf($key, $elements);
        }

        return isSubtype($key, of: $this->keyType)
            && isSubtype($value, of: $this->itemType);
    }

    /**
     * @param  array<ArrayElement>  $elements
     */
    private function isShapeSuperOf(Type $key, array $elements): bool
    {
        if (!isSubtype($key, $this->keyType)) {
            return false;
        }

        foreach ($this->elements as $index => $element) {
            if (!isset($elements[$index])) {
                return false;
            }

            if ($elements[$index]->optional && !$element->optional) {
                return false;
            }

            if (!isSubtype($elements[$index]->type, of: $element->type)) {
                return false;
            }
        }

        return true;
    }
}
