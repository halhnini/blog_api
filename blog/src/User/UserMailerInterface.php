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
 * Class UserMailerInterface
 */
interface UserMailerInterface
{
    /**
     * Send welcome mail to given user.
     * Return `true` if the mail was sent successfully, `false` on failure.
     *
     * @param User $user
     *
     * @return bool
     */
    public function sendWelcomeMail(User $user): bool;
}
