<?php

declare(strict_types=1);

namespace Utils\Rector\Rector;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

final readonly class CustomEnumFactory
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function createFromClass(Class_ $classToReplace): Enum_
    {
        $shortClassNameOfReplacementClass = $this->nodeNameResolver->getShortName($classToReplace);
        $enumNode = new Enum_(
            name: $shortClassNameOfReplacementClass,
            attributes: [
                'startLine' => $classToReplace->getStartLine(),
                'endLine' => $classToReplace->getEndLine()
            ]
        );

        $enumNode->namespacedName = $classToReplace->namespacedName;
        $constantsOfReplacementClass = $classToReplace->getConstants();
        $enumNode->stmts = $classToReplace->getTraitUses();
        
        if ($constantsOfReplacementClass !== []) {
            foreach ($constantsOfReplacementClass as $constant) {
                $enumNode->stmts[] = $this->createEnumCaseFromConst($constant);
            }
        }
        
        $enumNode->stmts = \array_merge($enumNode->stmts, $classToReplace->getMethods());
        return $enumNode;
    }

    private function createEnumCaseFromConst(ClassConst $classConst) : EnumCase
    {
        $constConst = $classConst->consts[0];
        $enumCase = new EnumCase(
            name: $constConst->name,
            attributes: ['startLine' => $constConst->getStartLine(), 'endLine' => $constConst->getEndLine()]
        );

        $enumCase->setAttribute(AttributeKey::PHP_DOC_INFO, $classConst->getAttribute(AttributeKey::PHP_DOC_INFO));
        $enumCase->setAttribute(AttributeKey::COMMENTS, $classConst->getAttribute(AttributeKey::COMMENTS));

        return $enumCase;
    }
}
