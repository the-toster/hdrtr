<?php

declare(strict_types=1);

namespace App\Infrastructure\TyphoonHydrator;

use Typhoon\Type\Type;

use function Typhoon\TypeStringifier\stringify;

final readonly class Error
{
    /**
     * @param  list<string|int>  $path
     */
    public function __construct(
        public array $path,
        public string $message,
    ) {
    }

    /**
     * @param  list<string|int>  $path
     */
    public static function unsupportedType(Type $targetType, array $path): self
    {
        return new self(
            $path,
            'Unsupported type: '
            .stringify($targetType)
        );
    }

    /**
     * @param  list<string|int>  $path
     */
    public static function unexpectedValue(Type $targetType, mixed $data, array $path): self
    {
        return new self(
            $path,
            'Unexpected value: '
            .var_export($data, true)
            .' for '
            .stringify($targetType)
        );
    }

    /**
     * @param  list<string|int>  $path
     */
    public static function missedOffset(int|string $key, array $path): self
    {
        return new self(
            $path,
            'Missed key: '.$key
        );
    }

    public function toString(): string
    {
        $path = $this->path !== []
            ? 'Path: '.implode('.', $this->path).'. '
            : '';

        return $path.$this->message;
    }
}