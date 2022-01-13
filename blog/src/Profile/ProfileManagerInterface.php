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
    Exception\ProfileDomainException
};
use Doctrine\ORM\{
    EntityNotFoundException,
    ORMException
};

/**
 * Interface ProfileManagerInterface
 */
interface ProfileManagerInterface
{
    /**
     * Create & save a new profile to database.
     *
     * @param ProfileData $profileData
     *
     * @return AbstractProfile
     *
     * @throws ProfileDomainException
     * @throws \InvalidArgumentException
     * @throws ORMException
     */
    public function addProfile(ProfileData $profileData): AbstractProfile;

    /**
     * Update & save an existing profile.
     *
     * @param ProfileData $profileData
     *
     * @return AbstractProfile
     *
     * @throws ProfileDomainException
     * @throws \InvalidArgumentException
     * @throws ORMException
     */
    public function updateProfile(ProfileData $profileData): AbstractProfile;

    /**
     * Create a profile & hydrate it with given data.
     *
     * @param ProfileData $profileData
     *
     * @return AbstractProfile
     *
     * @throws ProfileDomainException
     * @throws \InvalidArgumentException
     */
    public function createProfile(ProfileData $profileData): AbstractProfile;

    /**
     * Persist the given profile & save it to DB if $flush = true.
     *
     * @param AbstractProfile $user
     * @param bool            $flush
     *
     * @return void
     *
     * @throws ORMException
     * @throws \InvalidArgumentException
     */
    public function saveProfile(AbstractProfile $user, bool $flush = false): void;

    /**
     * Fetch the profile for the given user ID.
     *
     * @param int $userId
     *
     * @return AbstractProfile
     *
     * @throws EntityNotFoundException
     */
    public function fetchUserProfile(int $userId): AbstractProfile;

    /**
     * Fetch the profile for the given profile ID.
     *
     * @param int $profileId
     *
     * @return AbstractProfile
     *
     * @throws EntityNotFoundException
     */
    public function fetchProfile(int $profileId): AbstractProfile;
}
