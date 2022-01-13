<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\EventListener\Doctrine;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class BlameEntityListener
 */
class BlameEntityListener
{
    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * BlameEntityListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param mixed $entity
     */
    public function prePersist($entity): void
    {
        if (!method_exists($entity, 'setCreatorId') || !is_callable([$entity, 'setCreatorId'])) {
            return;
        }

        $entity->setCreatorId($this->getConnectedUserId());
    }

    /**
     * @return int|null
     */
    private function getConnectedUserId(): ?int
    {
        $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
        if (!$user instanceof User) {
            return null;
        }

        return $user->getId();
    }
}
