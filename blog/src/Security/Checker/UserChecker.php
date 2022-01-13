<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Security\Checker;

use App\Entity\User;
use Symfony\Component\Security\Core\{
    Exception\DisabledException,
    User\UserCheckerInterface,
    User\UserInterface
};

/**
 * Class UserChecker
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * {@inheritDoc}
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isActive()) {
            throw new DisabledException();
        }
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     */
    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }
}
