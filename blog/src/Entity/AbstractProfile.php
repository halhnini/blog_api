<?php

namespace App\Entity;

use App\Traits\{
    CreatedAtTrait,
    UpdatedAtTrait,
};
use Symfony\Component\{
    Serializer\Annotation\DiscriminatorMap,
    Validator\Constraints as Assert,
    Serializer\Annotation\Groups
};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Profile
 *
 * @ORM\Table(name="abstact_profile")
 * @ORM\Entity()
 * @ORM\EntityListeners({"App\EventListener\Doctrine\TimestampEntityListener"})
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="profile_type", type="string", length=32)
 * @ORM\DiscriminatorMap(
 * {
 *     Administrator::DISCRIMINATOR_COLUMN = Administrator::class,
 *     Author::DISCRIMINATOR_COLUMN = Author::class,
 *     Editor::DISCRIMINATOR_COLUMN = Editor::class,
 *     Contributor::DISCRIMINATOR_COLUMN = Contributor::class,
 *     Subscriber::DISCRIMINATOR_COLUMN = Subscriber::class
 * }
 * )
 *
 * @DiscriminatorMap(typeProperty="profileType", mapping={
 *     Administrator::DISCRIMINATOR_COLUMN = Administrator::class,
 *     Author::DISCRIMINATOR_COLUMN = Author::class,
 *     Editor::DISCRIMINATOR_COLUMN = Editor::class,
 *     Contributor::DISCRIMINATOR_COLUMN = Contributor::class,
 *     Subscriber::DISCRIMINATOR_COLUMN = Subscriber::class
 * })
 *
 * @UniqueEntity(
 *     fields={"user"},
 *     errorPath="user",
 *     groups={"create", "update"},
 *     message="validation.profile.user.unique_entity"
 * )
 */
abstract class AbstractProfile
{
    use UpdatedAtTrait, CreatedAtTrait;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     *
     * @Groups({"profile_create", "profile_update", "profile_detail", "company_identity_detail"})
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @ORM\OneToOne(targetEntity="User")
     *
     * @Assert\NotNull(groups={"create", "update"}, message="validation.profile.user.not_null")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     *
     * @Groups({"profile_create", "profile_update", "profile_detail"})
     *
     * @Assert\NotBlank(groups={"create", "update"}, message="validation.profile.firstName.not_blank")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     *
     * @Groups({"profile_create", "profile_update", "profile_detail"})
     *
     * @Assert\NotBlank(groups={"create", "update"}, message="validation.profile.lastName.not_blank")
     */
    private $lastName;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=16)
     *
     * @Groups({"profile_create", "profile_update", "profile_detail"})
     *
     * @Assert\NotBlank(groups={"create", "update"}, message="validation.profile.phone.not_blank")
     * @Assert\Length(max=16, maxMessage="validation.profile.phone.length.max")
     */
    private $phone;

    /**
     * @var int
     *
     * @ORM\Column(name="gender", type="smallint")
     *
     * @Assert\NotNull(groups={"create", "update"}, message="validation.profile.gender.not_null")
     *
     * @Groups({"profile_create", "profile_update", "profile_detail"})
     */
    private $gender;

    /**
     * @var bool
     *
     * @Groups({"profile_create", "profile_update", "profile_detail"})
     *
     * @ORM\Column(name="is_valid", type="boolean", options={"default"=false})
     */
    private $valid = false;

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
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getGender(): ?int
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     *
     * @return $this
     */
    public function setGender(int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @param bool $valid
     *
     * @return $this
     */
    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::DISCRIMINATOR_COLUMN;
    }
}
