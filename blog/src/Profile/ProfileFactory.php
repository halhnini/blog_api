<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Profile;

use App\{
    Entity\AbstractProfile,
    Entity\Administrator,
    Entity\Author,
    Entity\Editor,
    Entity\Contributor,
    Entity\Subscriber,
    Utils\ProfileHelper
};

/**
 * Class ProfileFactory
 */
class ProfileFactory
{
    /**
     * @param string $type
     *
     * @return AbstractProfile
     *
     * @throws \InvalidArgumentException
     */
    public static function create(string $type): AbstractProfile
    {
        switch ($type) {
            case ProfileHelper::ADMINISTRATOR_PROFILE:
                return new Administrator();
            case ProfileHelper::AUTHOR_PROFILE:
                return new Author();
            case ProfileHelper::EDITOR_PROFILE:
                return new Editor();
            case ProfileHelper::CONTRIBUTOR_PROFILE:
                return new Contributor();
            case ProfileHelper::SUBSCRIBER_PROFILE:
                return new Subscriber();
            default:
                throw new \InvalidArgumentException(sprintf('Invalid profile type [%s] given!', $type));
        }
    }
}
