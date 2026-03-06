<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\DocBlockParser;
use Hdrtr\NameResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function Typhoon\Type\intT;
use function Typhoon\Type\listT;
use function Typhoon\Type\nullOrT;
use function Typhoon\Type\unionT;

use const Typhoon\Type\intT;

final class DocBlockParserTest extends TestCase
{
    #[Test]
    public function parse_complex_var(): void
    {
        $parser = new DocBlockParser();

        assertEquals(unionT(intT(2), intT(1)), $parser->parseVar('/** @var 2|1 $abc */', []));
    }

    #[Test]
    public function parse_nullable_var(): void
    {
        $parser = new DocBlockParser();

        assertEquals(
            nullOrT(intT),
            $parser->parseVar('/** @var ?int $abc */', [])
        );
    }

    public function parse_template(): void
    {
        $parser = new DocBlockParser();
        assertEquals(listT(intT), $parser->parseVar('/** @var list<T> $abc */', ['T' => intT]));
    }
}