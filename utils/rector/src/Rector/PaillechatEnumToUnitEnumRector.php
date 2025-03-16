<?php

declare(strict_types=1);

namespace Utils\Rector\Rector;

use Paillechat\Enum\Enum;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\Exception\PoorDocumentationException;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class PaillechatEnumToUnitEnumRector extends AbstractRector implements
    MinPhpVersionInterface,
    ConfigurableRectorInterface
{
    private PaillechatEnumFqcnValueObject $enumToRefactor;

    public function __construct(
        private readonly CustomEnumFactory $enumFactory,
    ) {
        $this->enumToRefactor = PaillechatEnumFqcnValueObject::fromString(Enum::class);
    }

    /**
     * @throws PoorDocumentationException
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor Paillechat enum class to native Enum', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use \Paillechat\Enum\Enum;

/**
 * @method static self DRAFT()
 * @method static self PUBLISHED()
 * @method static self ARCHIVED()
 */
class StatusEnum extends Enum
{
    private const DRAFT = 'draft';
    private const PUBLISHED = 'published';
    private const ARCHIVED = 'archived';
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
enum StatusEnum
{
    case DRAFT;
    case PUBLISHED;
    case ARCHIVED;
}
CODE_SAMPLE,
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node)
    {
        if (!$this->isObjectType($node, new ObjectType((string) $this->enumToRefactor))) {
            return null;
        }

        return $this->enumFactory->createFromClass($node);
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
