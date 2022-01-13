<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Security\Voter;

use App\Repository\ProfileRepositoryInterface;
use Symfony\Component\Security\Core\{
    Authentication\Token\TokenInterface,
    Authorization\Voter\Voter
};
use App\Entity\User;

/**
 * Class UserVoter
 */
class UserVoter extends Voter
{
    const CREATE_PROFILE_ACTION = 'CREATE_PROFILE';
    const ALLOWED_ACTIONS = [
        self::CREATE_PROFILE_ACTION,
    ];

    /**
     * @var ProfileRepositoryInterface
     */
    private ProfileRepositoryInterface $profileRepository;

    /**
     * UserVoter constructor.
     *
     * @param ProfileRepositoryInterface $profileRepository
     */
    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * {@inheritDoc}
     */
    protected function supports(string $attribute, $subject)
    {
        return in_array($attribute, self::ALLOWED_ACTIONS) && $subject instanceof User;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $connectedUser = $token->getUser();
        if (!$connectedUser instanceof User) {
            return false;
        }

        return $this->canCreateProfile($connectedUser, $subject);
    }

    /**
     * @param User $connectedUser
     * @param User $subjectUser
     *
     * @return bool
     *
     * @throws \LogicException
     */
    private function canCreateProfile(User $connectedUser, User $subjectUser): bool
    {
        if ($connectedUser->getId() !== $subjectUser->getId()) {
            return false;
        }

        $profile = $this->profileRepository->find($connectedUser->getId());

        return is_null($profile);
    }
}
