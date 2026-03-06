<?php

declare(strict_types=1);

namespace Hdrtr;

use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayItemNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFalseNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFloatNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNullNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprTrueNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\CallableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ConditionalTypeForParameterNode;
use PHPStan\PhpDocParser\Ast\Type\ConditionalTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\InvalidTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeNode;
use PHPStan\PhpDocParser\Ast\Type\OffsetAccessTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use Typhoon\Type;

final readonly class DocBlockTypeReflector
{
    /**
     * @param array<string,Type> $templateArguments
     */
    public function __construct(
        private array $templateArguments = [],
        private ?NameResolver $nameResolver = null,
        private Type $thisType = Type\objectT,
    ) {
    }

    public function reflect(TypeNode $type): Type
    {
        return match ($type::class) {
            ArrayShapeNode::class => $this->arrayShape($type),
            ArrayTypeNode::class => $this->array($type),
            CallableTypeNode::class => $this->callable($type),
            ConditionalTypeForParameterNode::class => $this->conditionalTypeForParameter($type),
            ConditionalTypeNode::class => $this->conditional($type),
            ConstTypeNode::class => $this->constType($type),
            GenericTypeNode::class => $this->generic($type),
            IdentifierTypeNode::class => $this->identifier($type),
            IntersectionTypeNode::class => $this->intersection($type),
            InvalidTypeNode::class => Type\mixedT,
            NullableTypeNode::class => $this->nullable($type),
            ObjectShapeNode::class => $this->objectShape($type),
            OffsetAccessTypeNode::class => $this->offsetAccess($type),
            ThisTypeNode::class => $this->thisTypeNode($type),
            UnionTypeNode::class => $this->union($type),
            default => throw new \RuntimeException(),
        };
    }

    private function arrayShape(ArrayShapeNode $type): Type\ArrayT
    {
        $elements = [];
        foreach ($type->items as $index => $item) {
            $key = $item->keyName === null
                ? $index
                : $this->constExprArrayKey($item->keyName);

            $itemType = $this->reflect($item->valueType);

            $elements[$key] = $item->optional ? Type\optional($itemType) : $itemType;
        }

        $unsealedType = $type->unsealedType;
        if ($unsealedType === null) {
            return Type\arrayShapeT($elements);
        }

        $keyType = $unsealedType->keyType === null
            ? Type\arrayKeyT
            : $this->reflect($unsealedType->keyType);

        return Type\unsealedArrayShapeT(
            elements: $elements,
            key: $keyType,
            value: $this->reflect($type->unsealedType->valueType),
        );
    }

    private function array(ArrayTypeNode $type): Type\ArrayT
    {
        return Type\arrayT(value: $this->reflect($type));
    }

    private function callable(CallableTypeNode $_type): Type\CallableT
    {
        return Type\callableT();
    }

    private function conditional(ConditionalTypeNode $type): Type
    {
        return Type\unionT($this->reflect($type->if), $this->reflect($type->else));
    }

    private function conditionalTypeForParameter(ConditionalTypeForParameterNode $type): Type
    {
        return Type\unionT($this->reflect($type->if), $this->reflect($type->else));
    }

    private function constType(ConstTypeNode $type): Type
    {
        return $this->constExpr($type->constExpr);
    }

    private function constExprArrayKey(ConstExprNode|IdentifierTypeNode $node): string|int
    {
        if ($node instanceof ConstFetchNode) {
            $key = constant((string) $node);
            if (!is_string($key) && !is_int($key)) {
                throw new \RuntimeException('invalid array key const: ' . $node);
            }
            return $key;
        }

        return match ($node::class) {
            ConstExprIntegerNode::class => $node->value,
            ConstExprStringNode::class => $node->value,
            IdentifierTypeNode::class => $node->name,
            default => throw new \RuntimeException('unexpected array key const expression')
        };
    }

    private function constExpr(ConstExprNode $node): Type
    {
        return match ($node::class) {
            ConstExprArrayItemNode::class => $this->constExpr($node->value),
            ConstExprArrayNode::class => $this->constExprArrayNode($node),
            ConstExprFalseNode::class => Type\falseT,
            ConstExprFloatNode::class => Type\floatT((float) $node->value),
            ConstExprIntegerNode::class => Type\intT((int) $node->value),
            ConstExprNullNode::class => Type\nullT,
            ConstExprStringNode::class => Type\stringT($node->value),
            ConstExprTrueNode::class => Type\trueT,
            ConstFetchNode::class => TyphoonFactory::constant($node),
            default => throw new \RuntimeException()
        };
    }


    private function constExprArrayNode(ConstExprArrayNode $node): Type\ArrayT
    {
        $elements = [];
        foreach ($node->items as $index => $item) {
            $key = $item->key === null
                ? $index
                : $this->constExprArrayKey($item->key);
            $elements[$key] = $this->constExpr($item->value);
        }

        return Type\arrayShapeT($elements);
    }

    private function generic(GenericTypeNode $type): Type
    {
        return $this->identifier($type->type, array_values($type->genericTypes));
    }

    /**
     * @param list<TypeNode> $genericTypes
     */
    private function identifier(IdentifierTypeNode $type, array $genericTypes = []): Type
    {
        $templates = array_map($this->reflect(...), $genericTypes);
        $hasTwoTemplates = isset($templates[0], $templates[1]);
        $firstTemplate = $templates[0] ?? Type\mixedT;

        return match ($type->name) {
            'null' => Type\nullT,
            'true' => Type\trueT,
            'false' => Type\falseT,
            'bool' => Type\boolT,
            'int' => Type\intT,
            'positive-int' => Type\positiveIntT,
            'negative-int' => Type\negativeIntT,
            'non-negative-int' => Type\nonNegativeIntT,
            'non-positive-int' => Type\nonPositiveIntT,
            'non-zero-int' => Type\nonZeroIntT,
            'float' => Type\floatT,
            'string' => Type\stringT,
            'non-empty-string' => Type\nonEmptyStringT,
            'numeric-string' => Type\numericStringT,
            'lowercase-string' => Type\lowercaseStringT,
            'truthy-string', 'non-falsy-string' => Type\truthyStringT,
            'array-key' => Type\arrayKeyT,
            'numeric' => Type\numericT,
            'scalar' => Type\scalarT,
            'array' => $hasTwoTemplates
                ? Type\arrayT($templates[0], $templates[1])
                : Type\arrayT(value: $firstTemplate),
            'non-empty-array' => $hasTwoTemplates
                ? Type\nonEmptyArrayT($templates[0], $templates[1])
                : Type\nonEmptyArrayT(value: $firstTemplate),
            'list' => Type\listT($firstTemplate),
            'non-empty-list' => Type\nonEmptyListT($firstTemplate),
            'iterable' => $hasTwoTemplates
                ? Type\iterableT($templates[0], $templates[1])
                : Type\iterableT(value: $firstTemplate),
            'class-string' => Type\classT($templates[0] ?? Type\objectT),
            'object' => Type\objectT,
            'callable' => Type\callableT,
            'resource' => Type\resourceT,
            'mixed' => Type\mixedT,
            'void' => Type\voidT,
            'never', 'never-return', 'never-returns', 'no-return' => Type\neverT,
            'self', 'static', '$this' => $this->thisType,
            default => $this->templateArguments[$type->name]
                ?? TyphoonFactory::object(
                    $this->nameResolver?->resolve($type->name)
                    ?? $type->name
                )
        };
    }

    private function intersection(IntersectionTypeNode $type): Type
    {
        return TyphoonFactory::intersection(array_map($this->reflect(...), $type->types));
    }

    private function union(UnionTypeNode $type): Type
    {
        return TyphoonFactory::union(array_map($this->reflect(...), $type->types));
    }

    private function nullable(NullableTypeNode $type): Type
    {
        return Type\unionT($this->reflect($type->type), Type\nullT);
    }

    private function objectShape(ObjectShapeNode $type): Type
    {
        $props = [];
        foreach ($type->items as $item) {
            $name = match ($item->keyName::class) {
                ConstExprStringNode::class => $item->keyName->value,
                IdentifierTypeNode::class => $item->keyName->name,
                default => throw new \RuntimeException(),
            };
            $type = $this->reflect($item->valueType);

            if ($name === '') {
                throw new \RuntimeException();
            }

            $props[$name] = $item->optional
                ? Type\optional($type)
                : $type;
        }
        return Type\objectShapeT($props);
    }

    private function offsetAccess(OffsetAccessTypeNode $type): Type
    {
        throw new Unimplemented();
    }

    private function thisTypeNode(ThisTypeNode $_type): Type
    {
        return $this->thisType;
    }
}