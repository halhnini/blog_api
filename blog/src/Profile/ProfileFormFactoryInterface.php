<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Profile;

use Symfony\Component\Form\FormInterface;

/**
 * Interface ProfileFormFactoryInterface
 */
interface ProfileFormFactoryInterface
{
    /**
     * Create the form type for the given profile type.
     *
     * @param string     $profileType
     * @param mixed|null $data
     * @param array|null $options
     *
     * @return FormInterface
     *
     * @throws \InvalidArgumentException
     */
    public function create(string $profileType, $data = null, array $options = []): FormInterface;
}
