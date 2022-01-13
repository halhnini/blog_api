<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Profile;

use App\Entity\{
    AbstractProfile,
    User
};

/**
 * Class ProfileData
 */
class ProfileData
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    public $type;

    /**
     * @var array
     */
    public $formData;

    /**
     * @var AbstractProfile|null
     */
    public $profile;

    /**
     * ProfileData constructor.
     *
     * @param User                 $user
     * @param string               $type
     * @param array                $formData
     * @param AbstractProfile|null $profile
     */
    public function __construct(User $user, string $type, array $formData = [], AbstractProfile $profile = null)
    {
        $this->user = $user;
        $this->type = $type;
        $this->formData = $formData;
        $this->profile = $profile;
    }
}
