<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PostRepository
 */
class CommentRepository extends ServiceEntityRepository implements CommentRepositoryInterface
{
    const ALIAS = 'post';

    /**
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * CommentRepository constructor.
     *
     * @param ManagerRegistry     $registry
     * @param PaginatorInterface  $paginator
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;

        parent::__construct($registry, Comment::class);
    }

    /**
     * @param array $params
     * @param int   $postId
     *
     * @return PaginationInterface
     */
    public function fetchPostComments(array $params, int $postId): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('comment')
            ->andWhere('comment.post=:post')->setParameter('post', $postId)
            ->getQuery();

        return $this->paginator->paginate(
            $queryBuilder,
            $params['page'],
            $params['limit']
        );
    }
}
