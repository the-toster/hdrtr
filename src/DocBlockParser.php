<?php

declare(strict_types=1);

namespace Hdrtr;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use Typhoon\Type;

use const Typhoon\Type\mixedT;

final readonly class DocBlockParser
{
    public Lexer $lexer;
    public PhpDocParser $parser;

    public function __construct()
    {
        $config = new ParserConfig(usedAttributes: []);
        $this->lexer = new Lexer($config);
        $constExprParser = new ConstExprParser($config);
        $typeParser = new TypeParser($config, $constExprParser);
        $this->parser = new PhpDocParser($config, $typeParser, $constExprParser);
    }

    /**
     * @param array<string,Type> $templateArguments
     */
    public function parseVar(false|string $definition, array $templateArguments): ?Type
    {
        if ($definition === false) {
            return null;
        }

        $tokens = new TokenIterator($this->lexer->tokenize($definition));
        $varTags = $this->parser->parse($tokens)->getVarTagValues();

        $varTag = array_pop($varTags);

        return $varTag === null
            ? null
            : (new DocBlockTypeReflector($templateArguments))->reflect($varTag->type);
    }

    /** @return list<DocBlockTemplate> */
    public function parseTemplates(null|false|string $definition): array
    {
        if (!is_string($definition)) {
            return [];
        }

        $tokens = new TokenIterator($this->lexer->tokenize($definition));
        $r = [];
        $templateArguments = [];
        foreach ($this->parser->parse($tokens)->getTemplateTagValues() as $template) {
            $defaultNode = $template->default ?? $template->bound ?? $template->lowerBound ?? null;

            $defaultType = $defaultNode === null
                ? mixedT
                : (new DocBlockTypeReflector($templateArguments))->reflect($defaultNode);

            $r[] = new DocBlockTemplate($template->name, $defaultType);
            $templateArguments[$template->name] = $defaultType;
        }
        return $r;
    }


    /**
     * @param array<string,Type> $templateArguments
     * @return array<string,Type>
     */
    public function parseParam(null|false|string $definition, array $templateArguments): array
    {
        if (!is_string($definition)) {
            return [];
        }

        $reflector = (new DocBlockTypeReflector($templateArguments));
        $r = [];
        $tokens = new TokenIterator($this->lexer->tokenize($definition));
        foreach ($this->parser->parse($tokens)->getParamTagValues() as $param) {
            $name = ltrim($param->parameterName, '$');
            $r[$name] = $reflector->reflect($param->type);
        }

        return $r;
    }
}