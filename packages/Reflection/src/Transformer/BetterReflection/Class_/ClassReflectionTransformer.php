<?php declare(strict_types=1);

namespace ApiGen\Reflection\Transformer\BetterReflection\Class_;

use ApiGen\Element\Tree\ClassTraitElementResolver;
use ApiGen\Element\Tree\SubClassesResolver;
use ApiGen\Reflection\Contract\Reflection\Class_\ClassReflectionInterface;
use ApiGen\Reflection\Reflection\Class_\ClassReflection;
use ApiGen\Reflection\Contract\Transformer\TransformerInterface;
use ApiGen\Element\Tree\ParentClassElementsResolver;
use phpDocumentor\Reflection\DocBlockFactory;
use Roave\BetterReflection\Reflection\ReflectionClass;

final class ClassReflectionTransformer implements TransformerInterface
{
    /**
     * @var DocBlockFactory
     */
    private $docBlockFactory;

    /**
     * @var ParentClassElementsResolver
     */
    private $parentClassElementsResolver;

    /**
     * @var ClassTraitElementResolver
     */
    private $classTraitElementResolver;

    /**
     * @var SubClassesResolver
     */
    private $subClassesResolver;

    public function __construct(
        DocBlockFactory $docBlockFactory,
        ParentClassElementsResolver $parentClassElementsResolver,
        ClassTraitElementResolver $classTraitElementResolver,
        SubClassesResolver $subClassesResolver
    ) {
        $this->docBlockFactory = $docBlockFactory;
        $this->parentClassElementsResolver = $parentClassElementsResolver;
        $this->classTraitElementResolver = $classTraitElementResolver;
        $this->subClassesResolver = $subClassesResolver;
    }

    /**
     * @param object $reflection
     */
    public function matches($reflection): bool
    {
        return $reflection instanceof ReflectionClass && ! $reflection->isTrait() && ! $reflection->isInterface();
    }

    /**
     * @param object|ReflectionClass $reflection
     */
    public function transform($reflection): ClassReflectionInterface
    {
        $docBlock = $this->docBlockFactory->create($reflection->getDocComment() ?: ' ');

        return new ClassReflection(
            $reflection,
            $docBlock,
            $this->parentClassElementsResolver,
            $this->classTraitElementResolver,
            $this->subClassesResolver
        );
    }
}
