<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Profile;

use App\Entity\Contributor;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ContributorType
 */
class ContributorType extends AbstractProfileType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class', Contributor::class);
    }
}
