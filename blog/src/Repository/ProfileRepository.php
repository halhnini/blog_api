<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\AbstractProfile;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ProfileRepository
 */
class ProfileRepository extends ServiceEntityRepository implements ProfileRepositoryInterface
{
    const ALIAS = 'profile';

    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractProfile::class);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param mixed        $user
     */
    protected function addUserCriterion(QueryBuilder $queryBuilder, $user): void
    {
        if (empty($user)) {
            return;
        }

        $queryBuilder
            ->andWhere(sprintf('%s.user = :user', self::ALIAS))
            ->setParameter('user', $user)
        ;
    }
}
