<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Comment;

use App\{
    Entity\Comment,
    Exception\CommentException,
};
use Doctrine\ORM\{
    EntityNotFoundException,
    ORMException
};

/**
 * Interface CommentManagerInterface
 */
interface CommentManagerInterface
{
    /**
     * Create & save a new comment to database.
     *
     * @param array $commentData
     *
     * @return Comment
     */
    public function createComment(array $commentData): Comment;

    /**
     * @param Comment $comment
     * @param bool    $flush
     *
     * @throws ORMException
     * @throws \InvalidArgumentException
     */
    public function saveComment(Comment $comment, bool $flush = false): void;

    /**
     * @param Comment  $comment
     * @param array    $commentData
     *
     * @return Comment
     *
     * @throws CommentException
     */
    public function editComment(Comment $comment, array $commentData): Comment;
}
