<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\Type;

final readonly class Error
{
    public function __construct(
        string $message,
        Type $type,
        mixed $data,
        array $path
    )
    {
    }

    public static function failedToCast(Type $type, mixed $data, array $path): self
    {
        return new self('failed to cast', $type, $data, $path);
    }

    public static function missedKey(Type $type, mixed $data, array $path): self
    {
        return new self('failed to cast', $type, $data, $path);
    }

}