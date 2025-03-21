<?php

declare(strict_types=1);

namespace Utils\Rector\Rector;

use Paillechat\Enum\Enum;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

use function in_array;
use function is_string;
use function strtoupper;

class PaillechatEnumMethodCallToEnumConstRector extends AbstractRector implements
    MinPhpVersionInterface,
    ConfigurableRectorInterface
{
    /** @var string[] */
    private const ENUM_METHODS_TO_OMIT = ['createByName'];
    private const GET_NAME_METHOD = 'getName';
    private const GET_CONST_LIST_METHOD = 'getConstList';

    private PaillechatEnumFqcnValueObject $enumToRefactor;

    public function __construct()
    {
        $this->enumToRefactor = PaillechatEnumFqcnValueObject::fromString(Enum::class);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor Paillechat enum method calls', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$name1 = SomeEnum::SOME_CONSTANT()->getName();
$cases = SomeEnum::getConstList();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$name1 = SomeEnum::SOME_CONSTANT->name;
$name2 = SomeEnum::cases();
CODE_SAMPLE,
            ),
        ]);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @inheritDoc
     */
    public function refactor(Node $node)
    {
        if ($node->name instanceof Expr) {
            return null;
        }

        $enumCaseName = $this->getName($node->name);
        if ($enumCaseName === null) {
            return null;
        }

        if ($this->shouldOmitEnumCase($enumCaseName)) {
            return null;
        }

        if ($node instanceof MethodCall) {
            return $this->refactorMethodCall($node, $enumCaseName);
        }

        if (!$this->isObjectType($node->class, new ObjectType((string) $this->enumToRefactor))) {
            return null;
        }

        $className = $this->getName($node->class);
        if (!is_string($className)) {
            return null;
        }

        if ($node instanceof StaticCall && $enumCaseName === self::GET_CONST_LIST_METHOD) {
            return $this->nodeFactory->createStaticCall($className, 'cases');
        }

        $constantName = strtoupper($enumCaseName);
        return $this->nodeFactory->createClassConstFetch($className, $constantName);
    }

    private function refactorMethodCall(MethodCall $methodCall, string $methodName): ?PropertyFetch
    {
        if (!$this->isObjectType($methodCall->var, new ObjectType((string) $this->enumToRefactor))) {
            return null;
        }

        if ($methodName === self::GET_NAME_METHOD) {
            return $this->refactorGetterToPropertyFetch($methodCall, 'name');
        }

        return null;
    }

    private function refactorGetterToPropertyFetch(MethodCall $methodCall, string $property): ?PropertyFetch
    {
        if (!$methodCall->var instanceof StaticCall) {
            return null;
        }

        $staticCall = $methodCall->var;
        $className = $this->getName($staticCall->class);
        if ($className === null) {
            return null;
        }

        $enumCaseName = $this->getName($staticCall->name);
        if ($enumCaseName === null) {
            return null;
        }

        if ($this->shouldOmitEnumCase($enumCaseName)) {
            return null;
        }

        $upperCaseName = strtoupper($enumCaseName);
        $classConstFetch = $this->nodeFactory->createClassConstFetch($className, $upperCaseName);
        return new PropertyFetch($classConstFetch, $property);
    }

    private function shouldOmitEnumCase(string $enumCaseName): bool
    {
        return in_array($enumCaseName, self::ENUM_METHODS_TO_OMIT, true);
    }

    public function configure(array $configuration): void
    {
        $enumFqcn = $configuration[0] ?? PaillechatEnumFqcnValueObject::fromString(Enum::class);
        Assert::isAOf($enumFqcn, PaillechatEnumFqcnValueObject::class);

        $this->enumToRefactor = $enumFqcn;
    }
}
