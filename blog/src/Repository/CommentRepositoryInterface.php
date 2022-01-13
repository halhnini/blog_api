<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Repository;

use App\Entity\Comment;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CommentRepositoryInterface
 *
 * @method Comment find($id)
 * @method Comment findOneBy(array $criteria)
 * @method Comment[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method Comment[] findAll()
 * @method PaginationInterface fetchPostComments(array $params, int $postId)

 */
interface CommentRepositoryInterface
{
}
