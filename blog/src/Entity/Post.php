<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Entity;

use App\Traits\{
    CreatedAtTrait,
    UpdatedAtTrait
};
use Symfony\Component\{
    Serializer\Annotation\Groups,
    Validator\Constraints as Assert
};
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity()
 * @ORM\EntityListeners({"App\EventListener\Doctrine\TimestampEntityListener"})
 */
class Post
{
    use CreatedAtTrait, UpdatedAtTrait;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     *
     * @Groups({"post_detail", "post_list"})
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"post_create", "post_list"})
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     *
     * @Assert\NotNull(groups={"create", "update"}, message="validation.post.title.not_null")
     */
    private $title;

    /**
     * @var string
     *
     * @Groups({"post_create", "post_list"})
     *
     * @ORM\Column(name="content", type="text")
     *
     * @Assert\NotNull(groups={"create", "update"}, message="validation.post.content.not_null")
     */
    private $content;

    /**
     * @var AbstractProfile
     *
     * @Groups({"post_detail", "post_list"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\AbstractProfile")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id", unique=true, nullable=false)
     *
     * @Assert\NotNull(groups={"create"}, message="validation.post.profile.not_null")
     */
    private $profile;

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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return AbstractProfile|null
     */
    public function getProfile(): ?AbstractProfile
    {
        return $this->profile;
    }

    /**
     * @param AbstractProfile $profile
     *
     * @return $this
     */
    public function setProfile(AbstractProfile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }
}
