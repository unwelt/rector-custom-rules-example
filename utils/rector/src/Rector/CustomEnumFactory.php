<?php

declare(strict_types=1);

namespace Utils\Rector\Rector;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class CustomEnumFactory
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    public function createFromClass(Class_ $class): Enum_
    {
        $shortClassName = $this->nodeNameResolver->getShortName($class);
        $enum = new Enum_($shortClassName, [], ['startLine' => $class->getStartLine(), 'endLine' => $class->getEndLine()]);
        $enum->namespacedName = $class->namespacedName;
        $constants = $class->getConstants();
        $enum->stmts = $class->getTraitUses();
        
        if ($constants !== []) {
            foreach ($constants as $constant) {
                $enum->stmts[] = $this->createEnumCaseFromConst($constant);
            }
        }
        
        $enum->stmts = \array_merge($enum->stmts, $class->getMethods());
        return $enum;
    }

    private function createEnumCaseFromConst(ClassConst $classConst) : EnumCase
    {
        $constConst = $classConst->consts[0];
        $enumCase = new EnumCase($constConst->name, null, [], ['startLine' => $constConst->getStartLine(), 'endLine' => $constConst->getEndLine()]);

        $enumCase->setAttribute(AttributeKey::PHP_DOC_INFO, $classConst->getAttribute(AttributeKey::PHP_DOC_INFO));
        $enumCase->setAttribute(AttributeKey::COMMENTS, $classConst->getAttribute(AttributeKey::COMMENTS));

        return $enumCase;
    }
}
