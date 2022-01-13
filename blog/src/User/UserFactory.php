<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\User;

use App\Entity\User;

/**
 * Class UserFactory
 */
class UserFactory
{
    /**
     * @return User
     */
    public static function create(): User
    {
        return new User();
    }
}
