<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\IsValueTypeOf;
use PHPUnit\Framework\TestCase;

use Typhoon\Type\TrueT;

use function PHPUnit\Framework\assertTrue;

final class IsValueTypeOfTest extends TestCase
{
    public function testBasicTypes(): void
    {
        assertTrue(TrueT::T->accept(new IsValueTypeOf(true)));
    }
}