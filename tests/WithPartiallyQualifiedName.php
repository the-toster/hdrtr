<?php

declare(strict_types=1);

namespace Hdrtr\Tests;
use Hdrtr\Tests\Sub\Deep;

final readonly class WithPartiallyQualifiedName
{
    public function __construct(
        public Deep\Deep\SimpleObject $item,
    ) {
    }
}
