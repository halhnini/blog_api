<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Post;

use App\Entity\Post;

/**
 * Class PostFactory
 */
class PostFactory
{
    /**
     * @return Post
     */
    public static function create(): Post
    {
        return new Post();
    }
}
