<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\DocBlockParser;
use Hdrtr\NameResolver;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function Typhoon\Type\intT;
use function Typhoon\Type\listT;
use function Typhoon\Type\unionT;

use const Typhoon\Type\intT;

final class DocBlockParserTest extends TestCase
{
    public function testBasic(): void
    {
        $parser = new DocBlockParser(new NameResolver());

        assertEquals(unionT(intT(2), intT(1)), $parser->parseVar('/** @var 2|1 $abc */', []));
    }

    public function testGeneric(): void
    {
        $parser = new DocBlockParser();
        assertEquals(listT(intT), $parser->parseVar('/** @var list<T> $abc */', ['T' => intT]));
    }
}