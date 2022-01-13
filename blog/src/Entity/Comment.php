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
 * Class Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity()
 * @ORM\EntityListeners({"App\EventListener\Doctrine\TimestampEntityListener"})
 */
class Comment
{
    use CreatedAtTrait, UpdatedAtTrait;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     *
     * @Groups({"comment_detail"})
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"comment_create"})
     *
     * @ORM\Column(name="content", type="string")
     *
     * @Assert\NotNull(groups={"create", "update"}, message="validation.comment.content.not_null")
     */
    private $content;

    /**
     * @var Post
     *
     * @Groups({"comment_detail"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Post")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", unique=true, nullable=false)
     *
     * @Assert\NotNull(groups={"create"}, message="validation.comment.post.not_null")
     */
    private $post;

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
     * @return Post|null
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     *
     * @return $this
     */
    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
