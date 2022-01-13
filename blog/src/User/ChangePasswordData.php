<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\User;

use Symfony\Component\{
    Validator\Constraints as Assert,
    Security\Core\Validator\Constraints as SecurityAssert
};

/**
 * Class ChangePasswordData
 */
class ChangePasswordData
{
    /**
     * @var string
     *
     * @SecurityAssert\UserPassword(
     *     message = "invalid.user.old.password",
     *     groups={"reset_password"}
     * )
     */
    private string $oldPassword;

    /**
     * @var string
     *
     * @Assert\Regex(
     *     pattern="/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$/",
     *     message="invalid.user.new.password",
     *     groups={"reset_password"}
     * )
     */
    private string $newPassword;

    /**
     * Password constructor.
     *
     * @param string $oldPassword
     * @param string $newPassword
     */
    public function __construct(string $oldPassword, string $newPassword)
    {
        $this->newPassword = $newPassword;
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return string
     */
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }
}
