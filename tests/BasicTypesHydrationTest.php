<?php

declare(strict_types=1);

namespace Hdrtr\Tests;

use Hdrtr\Error;
use Hdrtr\Hydrator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use const Typhoon\Type\boolT;
use const Typhoon\Type\falseT;
use const Typhoon\Type\floatT;
use const Typhoon\Type\intT;
use const Typhoon\Type\mixedT;
use const Typhoon\Type\nullT;
use const Typhoon\Type\stringT;
use const Typhoon\Type\trueT;

final class BasicTypesHydrationTest extends TestCase
{
    private Hydrator $hydrator;

    protected function setUp(): void
    {
        $this->hydrator = new Hydrator();
    }

    /** @return iterable<string, array{mixed, mixed}> */
    public static function validCases(): iterable
    {
        yield 'null'   => [null,    nullT];
        yield 'true'   => [true,    trueT];
        yield 'false'  => [false,   falseT];
        yield 'bool'   => [true,    boolT];
        yield 'int'    => [42,      intT];
        yield 'float'  => [3.14,    floatT];
        yield 'string' => ['hello', stringT];
        yield 'mixed'  => ['any',   mixedT];
    }

    #[Test]
    #[DataProvider('validCases')]
    public function it_hydrates(mixed $data, mixed $type): void
    {
        assertEquals($data, $this->hydrator->hydrate($data, $type));
    }

    /** @return iterable<string, array{mixed, mixed}> */
    public static function invalidCases(): iterable
    {
        yield 'string as int'  => ['hello', intT];
        yield 'int as string'  => [42,      stringT];
        yield 'null as bool'   => [null,    boolT];
        yield 'true as false'  => [true,    falseT];
        yield 'false as true'  => [false,   trueT];
        yield 'string as float'=> ['3.14',  floatT];
    }

    #[Test]
    #[DataProvider('invalidCases')]
    public function it_returns_error_on_type_mismatch(mixed $data, mixed $type): void
    {
        assertInstanceOf(Error::class, $this->hydrator->hydrate($data, $type));
    }
}
