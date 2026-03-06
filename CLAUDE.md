# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Development shell (Docker)
docker compose run --rm php bash

# Run all tests
docker compose -f docker/compose.yaml run --rm php vendor/bin/phpunit

# Run a single test file
docker compose -f docker/compose.yaml run --rm php vendor/bin/phpunit tests/HydratorTest.php

# Run a single test method
docker compose -f docker/compose.yaml run --rm php vendor/bin/phpunit --filter testMethodName

# Static analysis
./vendor/bin/phpstan
```

## Architecture

**Hdrtr** is a PHP 8.3+ library that hydrates raw data (arrays) into typed PHP objects using the [Typhoon type system](https://github.com/typhoon-php/type).

### implementation

**`src/` — library implementation (visitor-based)**
- `Hydrator` — entry point, calls `$type->accept(new HydratingVisitor($data))`
- `HydratingVisitor` — implements `Typhoon\Type\Visitor`, handles every type variant recursively. This is the core of the library.
- `IsSimpleValueTypeOf` — a second visitor that validates raw data against a type without hydrating; used as a fast-path check
- `IsNever` — visitor that checks whether a type resolves to `never`
- `ObjectInstantiator` — reflects a class, parses DocBlocks for `@template` bindings, creates instances via `newInstanceWithoutConstructor()`, then sets properties via reflection
- `NameResolver` — resolves short/qualified class names to FQCNs by parsing `use` statements from the source file
- `DocBlockParser` + `DocBlockTypeReflector` — parse PHPStan's phpdoc-parser AST into Typhoon `Type` objects
- `DocBlockTemplate` — value object for a `@template` binding
- `TyphoonFactory` — creates Typhoon `Type` instances from reflection
- `ReflectionTypeConverter` — converts PHP's native `ReflectionType` to Typhoon `Type`
- `CustomHydrator` — interface for user-provided custom hydration logic
- `Unimplemented` — exception for visitor methods not yet implemented
- `Error` — returned (not thrown) on hydration failure; carries message, expected type, actual value, and path

### Key design decisions

- **No constructors called**: objects are always created with `ReflectionClass::newInstanceWithoutConstructor()` and populated via `ReflectionProperty::setValue()`.
- **Errors as values**: `HydratingVisitor` returns `Error` objects rather than throwing exceptions; callers check via `instanceof Error`.
- **Generics via DocBlocks**: `@template T` on a class + `@var array<T>` on a property is resolved at hydration time by binding template names to concrete types passed as arguments.
- **Union types**: each member is tried in order; the first successful (non-Error) result wins.
- **Namespace**: `Hdrtr\` maps to `src/`; tests are `Hdrtr\Tests\` → `tests/`.
