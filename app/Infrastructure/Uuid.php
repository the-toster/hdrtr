<?php

declare(strict_types=1);

namespace App\Infrastructure;

final readonly class Uuid
{
    private function __construct(
        public string $uuid
    ) {
        if (!self::isValid($uuid)) {
            throw new \RuntimeException('invalid string');
        }
    }

    public static function fromString(string $uuid): self
    {
        return new self($uuid);
    }

    public static function uuid7(): self
    {
        return new self(\Ramsey\Uuid\Uuid::uuid7()->toString());
    }

    public function toString(): string
    {
        return $this->uuid;
    }

    public static function isValid(string $data): bool
    {
        return \Ramsey\Uuid\Uuid::isValid($data);
    }
}