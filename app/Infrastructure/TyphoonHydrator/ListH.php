<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\ArrayElement;
use Typhoon\Type\Type;

use Typhoon\Type\types;

use function Typhoon\TypeComparator\isSubtype;

/**
 * @psalm-suppress MixedAssignment
 * @psalm-suppress UnusedForeachValue
 * @psalm-suppress InvalidArrayAccess
 * @psalm-suppress MixedArrayOffset
 * @psalm-suppress MixedArgument
 * @psalm-suppress MixedMethodCall
 */
final class ListH extends DefaultTypeHydrator
{
    public function supports(Type $type): bool
    {
        return isSubtype($type, types::list());
    }

    /**
     * @return \Closure(HydratorSelector):mixed
     */
    public function list(Type $self, Type $value, array $elements): \Closure
    {
        return fn(HydratorSelector $hydrator): mixed => $this->hydrate($hydrator, $self, $value, $elements);
    }

    private function hydrate(HydratorSelector $hydrator, Type $self, Type $value, array $elements): mixed
    {
        return count($elements) > 0
            ? $this->hydrateListShape($hydrator, $elements)
            : $this->hydrateList($hydrator, $self, $value);
    }

    private function hydrateList(HydratorSelector $hydrator, Type $self, Type $value): mixed
    {
        if (!is_iterable($hydrator->data)) {
            return $hydrator->unexpectedValue($self);
        }

        $result = [];

        foreach ($hydrator->data as $index => $data) {
            if (!isset($hydrator->data[$index])) {
                return $hydrator->missedOffset($index);
            }

            $r = $value->accept($hydrator->next($index));

            if ($r instanceof Error) {
                return $r;
            }

            $result[] = $r;
        }

        return $result;
    }

    private function hydrateListShape(HydratorSelector $hydrator, array $elements): mixed
    {
        $result = [];
        foreach ($elements as $index => $type) {
            if (!isset($hydrator->data[$index])) {
                return $hydrator->missedOffset($index);
            }

            $r = $type->accept($hydrator->next($index));
            if ($r instanceof Error) {
                return $r;
            }

            $result[] = $r;
        }

        return $result;
    }
}