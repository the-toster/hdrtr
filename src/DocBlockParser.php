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

    public function parseVar(string $comment): Type|ReflectionError
    {
        $tokens = new TokenIterator($this->lexer->tokenize($comment));

        foreach ($this->parser->parse($tokens)->getVarTagValues() as $varTag) {
            return $this->reflectTypeNode($varTag->type);
        }

        return null;
    }
}