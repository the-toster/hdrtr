<?php

declare(strict_types=1);

namespace Hdrtr;

use Typhoon\DeclarationId\AnonymousClassId;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\Type\Type;
use Typhoon\Type\Visitor\DefaultTypeVisitor;

/**
 * @extends DefaultTypeVisitor<?string>
 */
final class FindClassName extends DefaultTypeVisitor
{

    public function namedObject(Type $type, NamedClassId|AnonymousClassId $classId, array $typeArguments): mixed
    {
        if ($type instanceof NamedClassId) {
            return $type->name;
        }
        return null;
    }

    protected function default(Type $type): null
    {
        return null;
    }


}