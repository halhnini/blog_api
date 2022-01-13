<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Form\Transformer;

use Doctrine\ORM\{
    EntityManagerInterface,
    Mapping\MappingException
};
use Symfony\Component\Form\{
    DataTransformerInterface,
    Exception\TransformationFailedException
};
use Doctrine\Persistence\ObjectRepository;

/**
 * Class EntityDataTransformer
 */
class EntityDataTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @var string
     */
    protected string $entityClass;

    /**
     * EntityDataTransformer constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param string                 $entityClass
     */
    public function __construct(EntityManagerInterface $entityManager, string $entityClass)
    {
        $this->entityManager = $entityManager;
        $this->entityClass = $entityClass;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        // Return value has received, it will be serialized by Serializer
        return $value;
    }

    /**
     * {@inheritdoc}
     *
     * @throws MappingException
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return null;
        }

        $identifier = $this->getIdentifier($value);

        return $this->getRepository()->find($identifier);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     *
     * @throws MappingException
     */
    protected function getIdentifier($value)
    {
        if (is_scalar($value)) {
            return $value;
        }

        $identifierName = $this->getIdentifierName();
        if (!is_array($value) || (empty($value[$identifierName]) || !is_scalar($value[$identifierName]))) {
            throw new TransformationFailedException('Missing entity identifier attribute!');
        }

        return $value[$identifierName];
    }

    /**
     * @return string
     *
     * @throws MappingException
     */
    protected function getIdentifierName(): string
    {
        $meta = $this->entityManager->getClassMetadata($this->entityClass);

        return $meta->getSingleIdentifierFieldName();
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository($this->entityClass);
    }
}
