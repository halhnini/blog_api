<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Repository;

use App\Entity\AbstractProfile;
use App\Entity\Post;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface PostRepositoryInterface
 *
 * @method Post find($id)
 * @method Post findOneBy(array $criteria)
 * @method Post[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method Post[] findAll()
 * @method PaginationInterface getPagedPosts(array $params)
 * @method PaginationInterface getPagedPostsByConnectedUser(array $params, AbstractProfile $profile)
 */
interface PostRepositoryInterface
{
}
