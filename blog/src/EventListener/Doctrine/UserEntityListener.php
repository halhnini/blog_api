<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\EventListener\Doctrine;

use App\{
    Entity\User,
    User\UserManagerInterface
};

/**
 * Class UserEntityListener
 */
class UserEntityListener
{
    /**
     * @var UserManagerInterface
     */
    private UserManagerInterface $userManager;

    /**
     * UserEntityListener constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param User $user
     */
    public function prePersist(User $user): void
    {
        $this->encodeUserPassword($user);
    }

    /**
     * @param User $user
     */
    public function preUpdate(User $user): void
    {
        $this->encodeUserPassword($user);
    }

    /**
     * @param User $user
     */
    private function encodeUserPassword(User $user): void
    {
        $this->userManager->encodeUserPassword($user);
        $user->eraseCredentials();
    }
}
