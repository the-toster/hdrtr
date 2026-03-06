<?php

declare(strict_types=1);

namespace Hdrtr;

final class NameResolver
{
    /**
     * @var ?array<string,string>
     */
    private ?array $names = null;

    /**
     * @param \ReflectionClass<object> $class
     */
    public function __construct(
        private readonly \ReflectionClass $class,
    ) {
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

        $filename = $this->class->getFileName();
        $names = is_string($filename) ? $this->parseFile($filename) : [];
        return $this->names = $names;
    }

    /**
     * @return array<string, string>
     */
    private function parseFile(string $filename): array
    {
        $contents = file_get_contents($filename);
        if ($contents === false) {
            throw new \RuntimeException();
        }
        $tokens = token_get_all($contents);
        $tokens = array_values(
            array_filter(
                $tokens,
                static fn ($t) => !is_array($t) || !in_array($t[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true),
            )
        );

        $names = [];
        $depth = 0;
        $i = 0;
        $n = count($tokens);

        while ($i < $n) {
            $tok = $tokens[$i];

            if ($tok === '{') {
                $depth++;
                $i++;
                continue;
            }
            if ($tok === '}') {
                $depth--;
                $i++;
                continue;
            }

            // only top-level use statements
            if (!is_array($tok) || $tok[0] !== T_USE || $depth !== 0) {
                $i++;
                continue;
            }

            $i++; // skip T_USE

            // skip 'use function'
            if ($i < $n && is_array($tokens[$i]) && $tokens[$i][0] === T_FUNCTION) {
                while ($i < $n && $tokens[$i] !== ';') {
                    $i++;
                }
                $i++;
                continue;
            }

            // skip 'const' keyword but parse the import
            if ($i < $n && is_array($tokens[$i]) && $tokens[$i][0] === T_CONST) {
                $i++;
            }

            $baseName = $this->readName($tokens, $i, $n);
            if ($i >= $n) {
                break;
            }

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
                if ($i < $n && $tokens[$i] === ';') {
                    $i++;
                }
            } elseif ($tokens[$i] === '{') {
                // use Foo\{Bar, Baz as Qux};
                $prefix = rtrim(ltrim($baseName, '\\'), '\\');
                $i++;
                while ($i < $n && $tokens[$i] !== '}') {
                    if ($tokens[$i] === ',') {
                        $i++;
                        continue;
                    }

                    $memberName = $this->readName($tokens, $i, $n);
                    if ($memberName === '') {
                        $i++;
                        continue;
                    }

                    if ($i < $n && is_array($tokens[$i]) && $tokens[$i][0] === T_AS) {
                        $i++;
                        if ($i < $n && is_array($tokens[$i]) && $tokens[$i][0] === T_STRING) {
                            $names[$tokens[$i][1]] = $prefix . '\\' . $memberName;
                            $i++;
                        }
                    } else {
                        $names[$this->lastName($memberName)] = $prefix . '\\' . $memberName;
                    }
                }
                if ($i < $n) {
                    $i++;
                } // }
                if ($i < $n && $tokens[$i] === ';') {
                    $i++;
                }
            } else {
                $i++;
            }
        }

        return $names;
    }

    /**
     * @param list<array{int,string,int}|string> $tokens
     */
    private function readName(array $tokens, int &$i, int $n): string
    {
        $nameTokens = [T_STRING, T_NS_SEPARATOR, T_NAME_QUALIFIED, T_NAME_FULLY_QUALIFIED];
        $name = '';
        while (
            $i < $n
            && is_array($tokens[$i])
            && in_array($tokens[$i][0], $nameTokens, true)
        ) {
            $name .= $tokens[$i][1];
            $i++;
        }
        return $name;
    }

    private function lastName(string $name): string
    {
        $pos = strrpos($name, '\\');
        return $pos === false ? $name : substr($name, $pos + 1);
    }
}
