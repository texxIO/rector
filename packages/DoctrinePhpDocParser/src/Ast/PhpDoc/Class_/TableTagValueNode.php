<?php declare(strict_types=1);

namespace Rector\DoctrinePhpDocParser\Ast\PhpDoc\Class_;

use Rector\DoctrinePhpDocParser\Ast\PhpDoc\AbstractDoctrineTagValueNode;

final class TableTagValueNode extends AbstractDoctrineTagValueNode
{
    /**
     * @var string
     */
    public const SHORT_NAME = '@ORM\Table';

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $schema;

    /**
     * @var IndexTagValueNode[]
     */
    private $indexes = [];

    /**
     * @var UniqueConstraintTagValueNode[]
     */
    private $uniqueConstraints = [];

    /**
     * @var mixed[]
     */
    private $options = [];

    /**
     * @param mixed[] $options
     * @param IndexTagValueNode[] $indexes
     * @param UniqueConstraintTagValueNode[] $uniqueConstraints
     */
    public function __construct(
        ?string $name,
        ?string $schema,
        array $indexes,
        array $uniqueConstraints,
        array $options,
        ?string $originalContent = null
    ) {
        $this->name = $name;
        $this->schema = $schema;
        $this->indexes = $indexes;
        $this->uniqueConstraints = $uniqueConstraints;
        $this->options = $options;

        if ($originalContent !== null) {
            $this->resolveOriginalContentSpacingAndOrder($originalContent);
        }
    }

    public function __toString(): string
    {
        $contentItems = [];

        if ($this->name !== null) {
            $contentItems['name'] = sprintf('name="%s"', $this->name);
        }

        if ($this->schema !== null) {
            $contentItems['schema'] = sprintf('schema="%s"', $this->schema);
        }

        if ($this->indexes !== []) {
            $contentItems['indexes'] = $this->printNestedTag($this->indexes, IndexTagValueNode::SHORT_NAME, 'indexes');
        }

        if ($this->uniqueConstraints !== []) {
            $contentItems['uniqueConstraints'] = $this->printNestedTag(
                $this->uniqueConstraints,
                UniqueConstraintTagValueNode::SHORT_NAME,
                'uniqueConstraints'
            );
        }

        if ($this->options !== []) {
            $contentItems['options'] = $this->printArrayItem($this->options, 'options');
        }

        return $this->printContentItems($contentItems);
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
