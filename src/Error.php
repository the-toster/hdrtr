<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\Type;

final readonly class Error
{
    /**
     * @param list<string> $path
     */
    public function __construct(
        public string $message,
        public Type $type,
        public mixed $data,
        public array $path
    ) {
    }

    /**
     * @param list<string> $path
     */
    public static function failedToCast(Type $type, mixed $data, array $path): self
    {
        return new self('failed to cast', $type, $data, $path);
    }

    /**
     * @param list<string> $path
     */
    public static function missedKey(Type $type, mixed $data, array $path): self
    {
        return new self('missed key', $type, $data, $path);
    }


    /**
     * @param list<string> $path
     */
    public static function shouldBeNonEmpty(Type\ArrayT $type, mixed $data, array $path): self
    {
        return new self('data should by non empty', $type, $data, $path);
    }

}