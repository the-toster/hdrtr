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
        if (str_starts_with($name, '\\')) {
            return ltrim($name, '\\');
        }

        $nameMap = $this->buildNameMap();
        $parts = explode('\\', $name, 2);

        if (\array_key_exists($parts[0], $nameMap)) {
            return isset($parts[1])
                ? $nameMap[$parts[0]] . '\\' . $parts[1]
                : $nameMap[$parts[0]];
        }

        // resolve as namespaced name
        $namespace = $this->class->getNamespaceName();
        return $namespace !== '' ? $namespace . '\\' . $name : $name;
    }

    /**
     * @return array<string, string>
     */
    private function buildNameMap(): array
    {
        if ($this->names !== null) {
            return $this->names;
        }

        $names = [];

        // find $this->class file
        $filename = $this->class->getFileName();
        if ($filename !== false) {
            $tokens = token_get_all(file_get_contents($filename));
            $tokens = array_values(array_filter(
                $tokens,
                static fn($t) => !is_array($t) || !in_array($t[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]),
            ));

            $nameTokens = [T_STRING, T_NS_SEPARATOR, T_NAME_QUALIFIED, T_NAME_FULLY_QUALIFIED];
            $depth = 0;
            $i = 0;
            $n = count($tokens);

            while ($i < $n) {
                $tok = $tokens[$i];

                if ($tok === '{') { $depth++; $i++; continue; }
                if ($tok === '}') { $depth--; $i++; continue; }

                // find namespace node and parse 'use' nodes to find aliases at top level
                if (!is_array($tok) || $tok[0] !== T_USE || $depth !== 0) {
                    $i++;
                    continue;
                }

                $i++; // skip T_USE

                // skip 'use function'
                if ($i < $n && is_array($tokens[$i]) && $tokens[$i][0] === T_FUNCTION) {
                    while ($i < $n && $tokens[$i] !== ';') $i++;
                    $i++;
                    continue;
                }

                // skip 'use const' keyword, but parse the import
                if ($i < $n && is_array($tokens[$i]) && $tokens[$i][0] === T_CONST) {
                    $i++;
                }

                // read base name
                $baseName = '';
                while ($i < $n && is_array($tokens[$i]) && in_array($tokens[$i][0], $nameTokens)) {
                    $baseName .= $tokens[$i][1];
                    $i++;
                }

                if ($i >= $n) break;

                if ($tokens[$i] === ';') {
                    // use Foo\Bar;
                    $names[$this->lastName($baseName)] = ltrim($baseName, '\\');
                    $i++;
                } elseif (is_array($tokens[$i]) && $tokens[$i][0] === T_AS) {
                    // use Foo\Bar as Baz;
                    $i++;
                    if ($i < $n && is_array($tokens[$i]) && $tokens[$i][0] === T_STRING) {
                        $names[$tokens[$i][1]] = ltrim($baseName, '\\');
                        $i++;
                    }
                    if ($i < $n && $tokens[$i] === ';') $i++;
                } elseif ($tokens[$i] === '{') {
                    // use Foo\{Bar, Baz as Qux};
                    $prefix = rtrim(ltrim($baseName, '\\'), '\\');
                    $i++;
                    while ($i < $n && $tokens[$i] !== '}') {
                        if ($tokens[$i] === ',') { $i++; continue; }

                        $memberName = '';
                        while ($i < $n && is_array($tokens[$i]) && in_array($tokens[$i][0], $nameTokens)) {
                            $memberName .= $tokens[$i][1];
                            $i++;
                        }

                        if ($memberName === '') { $i++; continue; }

                        if ($i < $n && is_array($tokens[$i]) && $tokens[$i][0] === T_AS) {
                            $i++;
                            if ($i < $n && is_array($tokens[$i]) && $tokens[$i][0] === T_STRING) {
                                $names[$tokens[$i][1]] = $prefix . '\\' . $memberName;
                                $i++;
                            }
                        } else {
                            // build names map localName => FQN
                            $names[$this->lastName($memberName)] = $prefix . '\\' . $memberName;
                        }
                    }
                    if ($i < $n) $i++; // skip }
                    if ($i < $n && $tokens[$i] === ';') $i++;
                } else {
                    $i++;
                }
            }
        }

        $this->names = $names;

        return $names;
    }

    private function lastName(string $name): string
    {
        $pos = strrpos($name, '\\');
        return $pos === false ? $name : substr($name, $pos + 1);
    }
}
