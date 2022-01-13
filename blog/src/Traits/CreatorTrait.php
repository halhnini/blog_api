<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait CreatorTrait
 */
trait CreatorTrait
{
    /**
     * @var int|null
     *
     * @ORM\Column(name="creator_id", type="integer", nullable=true)
     */
    private $creatorId;

    /**
     * @return int|null
     */
    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    /**
     * @param int|null $creatorId
     *
     * @return $this
     */
    public function setCreatorId(?int $creatorId): self
    {
        $this->creatorId = $creatorId;

        return $this;
    }
}
