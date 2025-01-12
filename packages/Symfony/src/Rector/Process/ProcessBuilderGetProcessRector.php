<?php declare(strict_types=1);

namespace Rector\Symfony\Rector\Process;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

/**
 * @see \Rector\Symfony\Tests\Rector\Process\ProcessBuilderGetProcessRector\ProcessBuilderGetProcessRectorTest
 */
final class ProcessBuilderGetProcessRector extends AbstractRector
{
    /**
     * @var string
     */
    private $processBuilderClass;

    public function __construct(string $processBuilderClass = 'Symfony\Component\Process\ProcessBuilder')
    {
        $this->processBuilderClass = $processBuilderClass;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Removes `$processBuilder->getProcess()` calls to $processBuilder in Process in Symfony, because ProcessBuilder was removed. This is part of multi-step Rector and has very narrow focus.',
            [
                new CodeSample(
                    <<<'PHP'
$processBuilder = new Symfony\Component\Process\ProcessBuilder;
$process = $processBuilder->getProcess();
$commamdLine = $processBuilder->getProcess()->getCommandLine();
PHP
                    ,
                    <<<'PHP'
$processBuilder = new Symfony\Component\Process\ProcessBuilder;
$process = $processBuilder;
$commamdLine = $processBuilder->getCommandLine();
PHP
                ),
            ]
        );
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectType($node, $this->processBuilderClass)) {
            return null;
        }

        if (! $this->isName($node, 'getProcess')) {
            return null;
        }

        return $node->var;
    }
}
