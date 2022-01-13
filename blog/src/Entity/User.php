<?php

namespace App\Entity;

use App\Traits\{
    CreatedAtTrait,
    CreatorTrait,
    UpdatedAtTrait,
};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity()
 * @ORM\EntityListeners({
 *     "App\EventListener\Doctrine\TimestampEntityListener",
 *     "App\EventListener\Doctrine\UserEntityListener",
 *     "App\EventListener\Doctrine\BlameEntityListener"
 * })
 *
 * @UniqueEntity(
 *     groups={"create"},
 *     fields={"email"},
 *     errorPath="email",
 *     message="validation.user.email.unique_entity"
 * )
 */
class User implements UserInterface
{
    use CreatedAtTrait, UpdatedAtTrait, CreatorTrait;
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     *
     * @Groups({"user_create"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Groups({"user_create"})
     *
     * @Assert\NotBlank(groups={"create"}, message="validation.user.email.not_blank")
     * @Assert\Email(groups={"create"}, message="validation.user.email.email")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var array
     *
     *@ORM\Column(type="json")
     */
    private $roles;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", options={"default": false})
     */
    private $active;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $lastLogin;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(groups={"create"}, message="validation.user.password.not_blank")
     * @Assert\Length(
     *     groups={"create"},
     *     min="8",
     *     max="16",
     *     allowEmptyString=false,
     *     minMessage="validation.user.password.length.min",
     *     maxMessage="validation.user.password.length.max"
     * )
     */
    private $plainPassword;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $token;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->active = false;
        $this->roles = [];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {
        return $this->roles ?? [SecurityHelper::ROLE_USER];
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function removeRole(string $role): self
    {
        if (false !== $key = array_search($role, $this->roles, true)) {
            unset($this->roles[$key]);
        }

        return $this;
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param bool $active
     *
     * @return $this
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @param \DateTime|null $lastLogin
     *
     * @return $this
     */
    public function setLastLogin(?\DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * @param string|null $plainPassword
     *
     * @return $this
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * {@inheritDoc}
     */
    public function getUserIdentifier()
    {
        return $this->email;
    }

    /**
     * {@inheritDoc}
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     *
     * @return $this
     */
    public function setToken(?string $token): User
    {
        $this->token = $token;

        return $this;
    }
}
