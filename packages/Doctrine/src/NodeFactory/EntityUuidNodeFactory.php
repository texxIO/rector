<?php

declare(strict_types=1);

namespace Rector\Doctrine\NodeFactory;

use Nette\Utils\Strings;
use PhpParser\BuilderFactory;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Property;
use Ramsey\Uuid\Uuid;
use Rector\Doctrine\PhpDocParser\Ast\PhpDoc\PhpDocTagNodeFactory;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockManipulator;

final class EntityUuidNodeFactory
{
    /**
     * @var BuilderFactory
     */
    private $builderFactory;

    /**
     * @var PhpDocTagNodeFactory
     */
    private $phpDocTagNodeFactory;

    /**
     * @var DocBlockManipulator
     */
    private $docBlockManipulator;

    public function __construct(
        BuilderFactory $builderFactory,
        PhpDocTagNodeFactory $phpDocTagNodeFactory,
        DocBlockManipulator $docBlockManipulator
    ) {
        $this->builderFactory = $builderFactory;
        $this->phpDocTagNodeFactory = $phpDocTagNodeFactory;
        $this->docBlockManipulator = $docBlockManipulator;
    }

    public function createTemporaryUuidProperty(): Property
    {
        $uuidPropertyBuilder = $this->builderFactory->property('uuid');
        $uuidPropertyBuilder->makePrivate();
        $uuidProperty = $uuidPropertyBuilder->getNode();

        $this->decoratePropertyWithUuidAnnotations($uuidProperty, true, false);

        return $uuidProperty;
    }

    /**
     * Creates:
     * $this->uid = \Ramsey\Uuid\Uuid::uuid4();
     */
    public function createUuidPropertyDefaultValueAssign(string $uuidVariableName): Expression
    {
        $thisUuidPropertyFetch = new PropertyFetch(new Variable('this'), $uuidVariableName);
        $uuid4StaticCall = new StaticCall(new FullyQualified(Uuid::class), 'uuid4');

        $assign = new Assign($thisUuidPropertyFetch, $uuid4StaticCall);

        return new Expression($assign);
    }

    /**
     * Creates:
     * public function __construct()
     * {
     *     $this->uid = \Ramsey\Uuid\Uuid::uuid4();
     * }
     */
    public function createConstructorWithUuidInitialization(Class_ $class, string $uuidVariableName): ClassMethod
    {
        $classMethodBuilder = $this->builderFactory->method('__construct');
        $classMethodBuilder->makePublic();

        // keep parent constructor call
        if ($this->hasParentClassConstructor($class)) {
            $parentClassCall = $this->createParentConstructCall();
            $classMethodBuilder->addStmt($parentClassCall);
        }

        $assign = $this->createUuidPropertyDefaultValueAssign($uuidVariableName);
        $classMethodBuilder->addStmt($assign);

        return $classMethodBuilder->getNode();
    }

    public function decoratePropertyWithUuidAnnotations(Property $property, bool $isNullable, bool $isId): void
    {
        $this->clearVarAndOrmAnnotations($property);
        $this->replaceIntSerializerTypeWithString($property);

        // add @var
        $this->docBlockManipulator->addTag($property, $this->phpDocTagNodeFactory->createVarTagUuidInterface());

        if ($isId) {
            // add @ORM\Id
            $this->docBlockManipulator->addTag($property, $this->phpDocTagNodeFactory->createIdTag());
        }

        $this->docBlockManipulator->addTag($property, $this->phpDocTagNodeFactory->createUuidColumnTag($isNullable));

        if ($isId) {
            $this->docBlockManipulator->addTag($property, $this->phpDocTagNodeFactory->createGeneratedValueTag());
        }
    }

    private function clearVarAndOrmAnnotations(Node $node): void
    {
        $docComment = $node->getDocComment();
        if ($docComment === null) {
            return;
        }

        $clearedDocCommentText = Strings::replace($docComment->getText(), '#^(\s+)\*(\s+)\@(var|ORM)(.*?)$#ms');
        $node->setDocComment(new Doc($clearedDocCommentText));
    }

    /**
     * See https://github.com/ramsey/uuid-doctrine/issues/50#issuecomment-348123520.
     */
    private function replaceIntSerializerTypeWithString(Node $node): void
    {
        $docComment = $node->getDocComment();
        if ($docComment === null) {
            return;
        }

        $stringTypeText = Strings::replace(
            $docComment->getText(),
            '#(\@Serializer\\\\Type\(")(int)("\))#',
            '$1string$3'
        );

        $node->setDocComment(new Doc($stringTypeText));
    }

    private function hasParentClassConstructor(Class_ $class): bool
    {
        $parentClassName = $class->getAttribute(AttributeKey::PARENT_CLASS_NAME);
        if ($parentClassName === null) {
            return false;
        }

        return method_exists($parentClassName, '__construct');
    }

    private function createParentConstructCall(): StaticCall
    {
        return new StaticCall(new Name('parent'), '__construct');
    }
}
