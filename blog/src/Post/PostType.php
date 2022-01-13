<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Post;

use App\Entity\{
    AbstractProfile,
    Post
};
use App\Form\EntitySelectorType;
use Symfony\Component\Form\{
    AbstractType,
    Extension\Core\Type\TextType,
    FormBuilderInterface
};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostType
 */
class PostType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', TextType::class)
        ;
        if (Request::METHOD_POST === $options['method']) {
            $builder
                ->add('profile', EntitySelectorType::class, [
                    'class' => AbstractProfile::class,
                ])
            ;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
