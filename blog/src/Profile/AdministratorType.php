<?php
/*
 * This file is part of a Geniuses project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Geniuses\Union\Api\Profile;

use Geniuses\Union\Api\Entity\Administrator;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AdministratorType
 */
class AdministratorType extends AbstractProfileType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class', Administrator::class);
    }
}
