<?php

declare (strict_types=1);

namespace Utils\Rector\Rector;

use Paillechat\Enum\Enum;
use PhpParser\Node;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Reflection\Php\PhpPropertyReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Enum\EnumCaseObjectType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector extends AbstractRector implements
    MinPhpVersionInterface,
    ConfigurableRectorInterface
{
    private const GET_NAME_METHOD = 'getName';
    private const PROPERTY_FETCH_NAME = 'name';

    private PaillechatEnumFqcnValueObject $enumToRefactor;

    public function __construct()
    {
        $this->enumToRefactor = PaillechatEnumFqcnValueObject::fromString(Enum::class);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removes recasting of the same type', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$string1 = (string) SomeEnum::SOME_CASE();
$string2 = (string) SomeEnum::SOME_CASE()->getName();
$string3 = (string) NativeEnum::SOME_CASE;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$string1 = SomeEnum::SOME_CASE->name;
$string2 = SomeEnum::SOME_CASE->name;
$string3 = NativeEnum::SOME_CASE->name;
CODE_SAMPLE,
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Cast::class];
    }

    /**
     * @param Cast $node
     */
    public function refactor(Node $node): ?Node
    {
        $nodeClass = \get_class($node);
        if ($nodeClass !== String_::class) {
            return null;
        }

        $nodeType = $this->nodeTypeResolver->getNativeType($node->expr);

        if ($nodeType instanceof EnumCaseObjectType) {
            return $this->nodeFactory->createPropertyFetch($node->expr, self::PROPERTY_FETCH_NAME);
        }

        if ($node->expr instanceof MethodCall && $this->getName($node->expr->name) === self::GET_NAME_METHOD) {
            /** @var Node\Expr $var */
            $var = $node->expr->var;
            $className = $this->getName($var->class);

            if (\is_string($className) && $var instanceof StaticCall && $this->isObjectType(
                $var,
                new ObjectType((string) $this->enumToRefactor),
            )) {
                $constantName = \strtoupper($this->getName($var->name));
                $property = $this->nodeFactory->createClassConstFetch($className, $constantName);
                return $this->nodeFactory->createPropertyFetch($property, self::PROPERTY_FETCH_NAME);
            }
        }

        if ($node->expr instanceof StaticCall && $this->isObjectType(
            $node->expr,
            new ObjectType((string) $this->enumToRefactor),
        )) {
            $constantName = \strtoupper($this->getName($node->expr->name));
            $className = $this->getName($node->expr->class);
            $property = $this->nodeFactory->createClassConstFetch($className, $constantName);
            return $this->nodeFactory->createPropertyFetch($property, self::PROPERTY_FETCH_NAME);
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }

    public function configure(array $configuration): void
    {
        $enumFqcn = $configuration[0] ?? PaillechatEnumFqcnValueObject::fromString(Enum::class);
        Assert::isAOf($enumFqcn, PaillechatEnumFqcnValueObject::class);

        $this->enumToRefactor = $enumFqcn;
    }
}
