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
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PostRepository
 */
class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    const ALIAS = 'post';

    /**
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * PostRepository constructor.
     *
     * @param ManagerRegistry    $registry
     * @param PaginatorInterface $paginator
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
    }

    /**
     * @param array $params
     *
    * @return PaginationInterface
    */
    public function getPagedPosts(array $params): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('post');

        return $this->paginator->paginate(
            $queryBuilder,
            $params['page'],
            $params['limit']
        );
    }

    /**
     * @param array            $params
     * @param AbstractProfile  $profile
     *
     * @return PaginationInterface
     */
    public function getPagedPostsByConnectedUser(array $params, AbstractProfile $profile): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('post')
            ->andWhere('post.profile=:profile')->setParameter('profile', $profile)
            ->getQuery();

        return $this->paginator->paginate(
            $queryBuilder,
            $params['page'],
            $params['limit']
        );
    }
}
