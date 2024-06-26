<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\DefaultTypeVisitor;
use Typhoon\Type\Type;

/**
 * @extends DefaultTypeVisitor<mixed>
 */
final class HydratorSelector extends DefaultTypeVisitor
{

    /**
     * @param  list<string|int>  $path
     */
    public function __construct(
        private TypeHydratorRegistry $hydratorRegistry,
        public readonly mixed $data,
        public readonly array $path,
    ) {
    }

    public function unexpectedValue(Type $self): Error
    {
        return Error::unexpectedValue($self, $this->data, $this->path);
    }

    public function missedOffset(int|string $offset): Error
    {
        return Error::missedOffset($offset, $this->path);
    }

    public function nextKey(int|string $key): self
    {
        if (!isset($this->data[$key])) {
            throw new \LogicException('invalid offset');
        }
        return new self(
            $this->hydratorRegistry,
            $key,
            [...$this->path, $key]
        );
    }

    public function unsupportedType(Type $self): Error
    {
        return Error::unsupportedType($self, $this->path);
    }

    public function next(int|string $offset): self
    {
        /**
         * @psalm-suppress MixedAssignment
         * @psalm-suppress MixedArrayAccess
         */
        $nextData = $this->data[$offset] ?? throw new \LogicException('invalid offset');
        return new self(
            $this->hydratorRegistry,
            $nextData,
            [...$this->path, $offset]
        );
    }

    public function union(Type $self, array $types): mixed
    {
        foreach ($types as $type) {
            /** @var mixed $r */
            $r = $type->accept($this);
            if ($r instanceof Error) {
                continue;
            }
            return $r;
        }
        /** @var Error $r */
        return $r;
    }

    protected function default(Type $self): mixed
    {
        $visitor = $this->hydratorRegistry->find($self);

        if ($visitor === null) {
            return $this->unsupportedType($self);
        }

        return $self->accept($visitor)($this);
    }
}