<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\User;

use App\Entity\AbstractProfile;
use App\Entity\User;
use App\Form\EntitySelectorType;
use Symfony\Component\{Form\AbstractType,
    Form\Extension\Core\Type\EmailType,
    Form\Extension\Core\Type\PasswordType,
    Form\FormBuilderInterface,
    HttpFoundation\Request,
    OptionsResolver\OptionsResolver};

/**
 * Class UserType
 */
class UserType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['required' => true])
            ->add('plainPassword', PasswordType::class, ['required' => true])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
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
