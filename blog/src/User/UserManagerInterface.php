<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\User;

use App\{
    Entity\User,
    Exception\UserDomainException
};
use Doctrine\ORM\{
    EntityNotFoundException,
    ORMException
};

/**
 * Class UserManagerInterface
 */
interface UserManagerInterface
{
    /**
     * @param array  $userData
     * @param string $profile
     *
     * @return User
     *
     * @throws UserDomainException
     * @throws \InvalidArgumentException
     * @throws ORMException
     */
    public function createUser(array $userData, string $profile): User;

    /**
     * Save the given user to database.
     *
     * @param User $user
     *
     * @return User
     *
     * @throws ORMException
     * @throws \InvalidArgumentException
     */
    public function saveUser(User $user): User;

    /**
     * Activate the given user
     *
     * @param User   $user
     * @param string $token
     *
     * @throws ORMException
     * @throws \InvalidArgumentException
     * @throws UserDomainException
     */
    public function activateUser(User $user, string $token): void;

    /**
     * Reset user password
     *
     * @param User   $user
     * @param string $oldPassword
     * @param string $newPassword
     *
     * @throws UserDomainException
     */
    public function resetUserPassword(User $user, string $oldPassword, string $newPassword): void;

    /**
     * Encode user password
     *
     * @param User $user
     */
    public function encodeUserPassword(User $user): void;

    /**
     * Find & return a user with the given ID, throw an exception if not found.
     *
     * @param int $userId
     *
     * @return User
     *
     * @throws EntityNotFoundException
     */
    public function fetchUser(int $userId): User;
}
