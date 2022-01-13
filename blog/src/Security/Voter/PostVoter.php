<?php

namespace App\Security\Voter;

use App\{Entity\AbstractProfile,
    Entity\Administrator,
    Entity\Post,
    Entity\User,
    Profile\ProfileData,
    Repository\PostRepositoryInterface,
    Repository\ProfileRepositoryInterface,
    Utils\ProfileHelper,
    Utils\SecurityHelper};
use Symfony\Component\Security\{
    Core\Authentication\Token\TokenInterface,
    Core\Authorization\Voter\Voter
};

/**
 * Class PostVoter
 */
class PostVoter extends Voter
{
    const PATCH_ACTION = 'PATCH_POST';
    const DELETE_ACTION = 'DELETE_POST';
    const ALLOWED_ACTIONS = [
        self::PATCH_ACTION,
        self::DELETE_ACTION,
    ];

    /**
     * @var PostRepositoryInterface
     */
    private PostRepositoryInterface $postRepository;

    /**
     * PostVoter constructor.
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
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, self::ALLOWED_ACTIONS) && $subject instanceof Post;
    }

    /**
     * {@inheritDoc}
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $connectedUser = $token->getUser();

        if (!$connectedUser instanceof User) {
            return false;
        }

        $roles = array_intersect([SecurityHelper::ROLE_SUPER_ADMIN, SecurityHelper::ROLE_EDITOR], $token->getRoleNames());
        if (!empty($roles)) {
            return true;
        }

        $result = false;
        switch ($attribute) {
            case self::PATCH_ACTION:
                $result = $this->canUpdate($connectedUser, $subject);
                break;
            case self::DELETE_ACTION:
                $result = $this->canDelete($connectedUser, $subject);
                break;
        }

        return $result;
    }

    /**
     * @param User $connectedUser
     * @param Post $post
     *
     * @return bool
     */
    private function canUpdate(User $connectedUser, Post $post): bool
    {
        return $connectedUser->getId() === $post->getProfile()->getUser()->getId();
    }

    /**
     * @param User $connectedUser
     * @param Post $post
     *
     * @return bool
     */
    private function canDelete(User $connectedUser, Post $post): bool
    {
        return $connectedUser->getId() === $post->getProfile()->getUser()->getId();
    }
}
