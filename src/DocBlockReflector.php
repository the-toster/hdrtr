<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\Type;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\ParserConfig;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;


final readonly class DocBlockReflector
{
    public function __construct(public Type\NamedObjectT $type)
    {
    }

    public function reflect(\ReflectionProperty $property): ?Type
    {
        $config = new ParserConfig(usedAttributes: []);
        $lexer = new Lexer($config);
        $constExprParser = new ConstExprParser($config);
        $typeParser = new TypeParser($config, $constExprParser);
        $phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);

        $comment = $property->getDocComment();
        if($comment === false) {
            return null;
        }

        $tokens = new TokenIterator($lexer->tokenize($comment));
        $phpDocNode = $phpDocParser->parse($tokens);

        return null;
    }

}