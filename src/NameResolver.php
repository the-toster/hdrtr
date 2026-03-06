<?php

declare(strict_types=1);

namespace Hdrtr;

final class NameResolver
{

    /**
     * @var ?array<string,string>
     */
    public ?array $names = null;

    public function __construct(
        private readonly \ReflectionClass $class,
    )
    {
    }

    public function resolve(string $name): string
    {
        $nameMap = $this->buildNameMap();
        if (\array_key_exists($name, $nameMap)) {
            return $nameMap[$name];
        }

        // resolve as namespaced name
    }

    /**
     * @return array<string, string>
     */
    private function buildNameMap(): array
    {
        if($this->names !== null) {
            return $this->names;
        }

        $names = [];

        // find $this->class file
        // find namespace node
        // parse 'use' nodes to find aliases
        // build names map localName => FQN

        $this->names = $names;

        return $names;
    }
}