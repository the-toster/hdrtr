<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\ArrayElement;
use Typhoon\Type\Type;

use Typhoon\Type\types;

use function Typhoon\TypeComparator\isSubtype;

final class ArrayH extends DefaultTypeHydrator
{
    public function supports(Type $type): bool
    {
        return isSubtype($type, types::array());
    }

    public function array(Type $self, Type $key, Type $value, array $elements): mixed
    {
        return fn(HydratorSelector $hydrator): mixed => $this->hydrate($hydrator, $self, $key, $value, $elements);
    }

    /**
     * @template TKey
     * @template TValue
     * @param  Type<TKey>  $key
     * @param  Type<TValue>  $value
     * @param  array<ArrayElement>  $elements
     */
    public function hydrate(HydratorSelector $hydrator, Type $self, Type $key, Type $value, array $elements): mixed
    {
        if (count($elements) > 0) {
            return $this->hydrateShape($hydrator, $self, $key, $elements);
        }
        $result = [];

        if (!is_array($hydrator->data)) {
            return $hydrator->unexpectedValue($self);
        }

        foreach (array_keys($hydrator->data) as $k) {
            /** @var Error|TKey $hydratedKey */
            $hydratedKey = $key->accept($hydrator->nextKey($k));

            if ($hydratedKey instanceof Error) {
                return $hydratedKey;
            }

            /** @var Error|TValue $hydratedValue */
            $hydratedValue = $value->accept($hydrator->next($k));
            if ($hydratedValue instanceof Error) {
                return $hydratedValue;
            }

            $result[$hydratedKey] = $hydratedValue;
        }

        return $result;
    }

    /**
     * @template TKey
     * @template TValue
     * @param  Type<TKey>  $self
     * @param  array<ArrayElement<TValue>>  $elements
     */
    private function hydrateShape(HydratorSelector $hydrator, Type $self, Type $key, array $elements): mixed
    {
        $result = [];

        if (!is_array($hydrator->data)) {
            return $hydrator->unexpectedValue($self);
        }

        foreach ($elements as $k => $v) {
            if (!isset($hydrator->data[$k])) {
                if ($v->optional) {
                    continue;
                }

                return $hydrator->missedOffset($k);
            }

            /**
             * @var Error|TKey $hydratedKey
             */
            $hydratedKey = $key->accept($hydrator->nextKey($k));

            if ($hydratedKey instanceof Error) {
                return $hydratedKey;
            }

            /**
             * @var Error|TValue $hydratedValue
             */
            $hydratedValue = $v->type->accept($hydrator->next($k));

            if ($hydratedValue instanceof Error) {
                return $hydratedValue;
            }

            $result[$hydratedKey] = $hydratedValue;
        }

        return $result;
    }

}