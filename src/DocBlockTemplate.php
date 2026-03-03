<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\Type;

final readonly class DocBlockTemplate
{
    public function __construct(
        public string $templateName,
        public Type $default,
    )
    {
    }
}