<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Comment;

use App\Entity\Comment;

/**
 * Class CommentFactory
 */
class CommentFactory
{
    /**
     * @return Comment
     */
    public static function create(): Comment
    {
        return new Comment();
    }
}
