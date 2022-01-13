<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Form;

use Symfony\Component\{
    Form\AbstractType,
    Form\DataTransformerInterface,
    Form\Extension\Core\Type\TextType,
    Form\FormBuilderInterface,
    OptionsResolver\OptionsResolver
};
use App\Form\Transformer\{
    EntitiesDataTransformer,
    EntityDataTransformer
};
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EntitySelectorType
 */
class EntitySelectorType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * EntitySelectorType constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(
            $this->getModelTransformer($options)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['class'])
            ->setDefined(['multiple'])
            ->setAllowedTypes('class', 'string')
            ->setAllowedTypes('multiple', 'boolean')
            ->setDefault('multiple', false)
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * @param array $options
     *
     * @return DataTransformerInterface
     */
    private function getModelTransformer(array $options): DataTransformerInterface
    {
        $transformerClass = EntityDataTransformer::class;
        if ($options['multiple']) {
            $transformerClass = EntitiesDataTransformer::class;
        }

        return new $transformerClass(
            $this->entityManager,
            $options['class']
        );
    }
}
