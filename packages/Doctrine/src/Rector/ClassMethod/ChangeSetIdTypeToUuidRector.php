<?php declare(strict_types=1);

namespace Rector\Doctrine\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Doctrine\ValueObject\DoctrineClass;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\RectorDefinition;

/**
 * @sponsor Thanks https://spaceflow.io/ for sponsoring this rule - visit them on https://github.com/SpaceFlow-app
 *
 * @see \Rector\Doctrine\Tests\Rector\ClassMethod\ChangeSetIdTypeToUuidRector\ChangeSetIdTypeToUuidRectorTest
 */
final class ChangeSetIdTypeToUuidRector extends AbstractRector
{
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Change param type of setId() to uuid interface');
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isInDoctrineEntityClass($node)) {
            return null;
        }

        if (! $this->isName($node, 'setId')) {
            return null;
        }

        $this->renameUuidVariableToId($node);

        // is already set?
        if ($node->params[0]->type) {
            $currentType = $this->getName($node->params[0]->type);
            if ($currentType === DoctrineClass::RAMSEY_UUID_INTERFACE) {
                return null;
            }
        }

        $node->params[0]->type = new FullyQualified(DoctrineClass::RAMSEY_UUID_INTERFACE);

        return $node;
    }

    private function renameUuidVariableToId(ClassMethod $classMethod): void
    {
        $this->traverseNodesWithCallable($classMethod, function (Node $node): ?Identifier {
            if (! $node instanceof Identifier) {
                return null;
            }

            if (! $this->isName($node, 'uuid')) {
                return null;
            }

            return new Identifier('id');
        });
    }
}
