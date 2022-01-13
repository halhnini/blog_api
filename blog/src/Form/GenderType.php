<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Form;

use Symfony\Component\Form\{
    AbstractType,
    Extension\Core\Type\TextType,
    FormBuilderInterface
};
use App\Form\Transformer\GenderDataTransformer;

/**
 * Class GenderType
 */
class GenderType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new GenderDataTransformer());
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
}
