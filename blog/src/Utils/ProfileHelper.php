<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Utils;

/**
 * Class ProfileHelper
 */
class ProfileHelper
{
    const AUTHOR_PROFILE = 'AUTHOR';
    const EDITOR_PROFILE = 'EDITOR';
    const CONTRIBUTOR_PROFILE = 'CONTRIBUTOR';
    const SUBSCRIBER_PROFILE = 'SUBSCRIBER';
    const ADMINISTRATOR_PROFILE = 'ADMINISTRATOR';
    const PROFILE_TYPES = [
        self::AUTHOR_PROFILE,
        self::EDITOR_PROFILE,
        self::CONTRIBUTOR_PROFILE,
        self::SUBSCRIBER_PROFILE,
        self::ADMINISTRATOR_PROFILE,
    ];
    const PROFILES_ROLES = [
        self::AUTHOR_PROFILE => SecurityHelper::ROLE_AUTHOR,
        self::EDITOR_PROFILE => SecurityHelper::ROLE_EDITOR,
        self::CONTRIBUTOR_PROFILE => SecurityHelper::ROLE_CONTRIBUTOR,
        self::SUBSCRIBER_PROFILE => SecurityHelper::ROLE_SUBSCRIBER,
        self::ADMINISTRATOR_PROFILE => SecurityHelper::ROLE_ADMIN,
    ];

    /**
     * Get profile role.
     *
     * @param string $profile
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public static function getProfileRole(string $profile): string
    {
        if (!array_key_exists($profile, self::PROFILES_ROLES)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid profile given (%s), available profiles are [%s] !',
                $profile,
                implode(',', array_keys(self::PROFILES_ROLES))
            ));
        }

        return self::PROFILES_ROLES[$profile];
    }
}
