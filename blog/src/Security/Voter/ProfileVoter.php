<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Security\Voter;

use App\{Entity\AbstractProfile,
    Entity\Administrator,
    Entity\User,
    Profile\ProfileData,
    Repository\PostRepositoryInterface,
    Utils\ProfileHelper,
    Utils\SecurityHelper};
use Symfony\Component\Security\Core\{
    Authentication\Token\TokenInterface,
    Authorization\Voter\Voter
};

/**
 * Class ProfileVoter
 */
class ProfileVoter extends Voter
{
    const PATCH_ACTION = 'PATCH_PROFILE';
    const GET_ACTION = 'GET_ACTION';
    const CREATE_POST = 'CREATE_POST';
    const CREATE_COMMENT = 'CREATE_COMMENT';
    const ALLOWED_ACTIONS = [
        self::PATCH_ACTION,
        self::GET_ACTION,
        self::CREATE_POST,
        self::CREATE_COMMENT,
    ];


    /**
     * @var PostRepositoryInterface
     */
    private PostRepositoryInterface $postRepository;

    /**
     * ProfileVoter constructor.
     *
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * {@inheritDoc}
     */
    protected function supports(string $attribute, $subject)
    {
        return in_array($attribute, self::ALLOWED_ACTIONS)
            && ($subject instanceof AbstractProfile || $subject instanceof ProfileData);
    }

    /**
     * {@inheritDoc}
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $connectedUser = $token->getUser();
        if (!$connectedUser instanceof User) {
            return false;
        }

        if (in_array(SecurityHelper::ROLE_SUPER_ADMIN, $token->getRoleNames())) {
            return true;
        }

        $result = false;
        switch ($attribute) {
            case self::PATCH_ACTION:
                $result = $this->canUpdate($connectedUser, $subject);
                break;
            case self::GET_ACTION:
                $result = $this->canRead($connectedUser, $subject);
                break;
            case self::CREATE_POST:
                $result = $this->canCreatePost($connectedUser, $subject);
                break;
            case self::CREATE_COMMENT:
                $result = $this->canCreateComment($connectedUser, $subject);
                break;
        }

        return $result;
    }

    /**
     * @param User        $connectedUser
     * @param ProfileData $profileData
     *
     * @return bool
     */
    private function canUpdate(User $connectedUser, ProfileData $profileData): bool
    {
        return $connectedUser->getId() === $profileData->user->getId()
            && !is_null($profileData->profile)
            && $connectedUser->getId() === $profileData->profile->getUser()->getId();
    }

    /**
     * @param User            $connectedUser
     * @param AbstractProfile $profile
     *
     * @return bool
     */
    private function canRead(User $connectedUser, AbstractProfile $profile): bool
    {
        if ($profile instanceof Administrator) {
            return $connectedUser->getId() === $profile->getUser()->getId();
        }

        return true;
    }

    /**
     * @param User            $connectedUser
     * @param AbstractProfile $profile
     *
     * @return bool
     */
    private function canCreatePost(User $connectedUser, AbstractProfile $profile): bool
    {
        $roles = array_intersect([
            SecurityHelper::ROLE_AUTHOR,
            SecurityHelper::ROLE_EDITOR,
            SecurityHelper::ROLE_CONTRIBUTOR
            ],
            $connectedUser->getRoles()
        );

        return $connectedUser === $profile->getUser() && !empty($roles);
    }

    /**
     * @param User            $connectedUser
     * @param AbstractProfile $profile
     *
     * @return bool
     */
    private function canCreateComment(User $connectedUser, AbstractProfile $profile): bool
    {
        return $connectedUser === $profile->getUser();
    }
}
