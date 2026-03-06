<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

enum BackedEnum: string
{
    case act = 'active';
    case inact = 'inactive';
}
