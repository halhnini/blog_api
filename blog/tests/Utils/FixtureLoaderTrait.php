<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Tests\Utils;

use Doctrine\Common\DataFixtures\{
    Executor\AbstractExecutor,
    Executor\ORMExecutor,
    Purger\ORMPurger,
    Purger\PurgerInterface
};
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;

/**
 * Trait FixtureLoaderTrait
 */
trait FixtureLoaderTrait
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @var SymfonyFixturesLoader
     */
    protected SymfonyFixturesLoader $fixturesLoader;

    /**
     * @var PurgerInterface
     */
    protected PurgerInterface $purger;

    /**
     * @var AbstractExecutor
     */
    protected AbstractExecutor $executor;

    /**
     * @param EntityManagerInterface $entityManager
     * @param SymfonyFixturesLoader  $fixturesLoader
     */
    protected function initLoader(EntityManagerInterface $entityManager, SymfonyFixturesLoader $fixturesLoader): void
    {
        $this->entityManager = $entityManager;
        $this->fixturesLoader = $fixturesLoader;
        $this->purger = new ORMPurger($this->entityManager);
        $this->executor = new ORMExecutor($this->entityManager, $this->purger);
    }

    /**
     * @param array $fixtures
     * @param bool  $purge
     */
    protected function loadFixtures(array $fixtures, bool $purge = true): void
    {
        $this->fixturesLoader->addFixtures($this->transformFixturesFormat($fixtures));

        $this->executor->execute($this->fixturesLoader->getFixtures(['default']), !$purge);
    }

    /**
     * Add a group by default for all fixtures.
     *
     * @param array $fixtures
     *
     * @return array
     */
    protected function transformFixturesFormat(array $fixtures): array
    {
        $output = [];
        foreach ($fixtures as $fixture) {
            $output[] = ['fixture' => $fixture, 'groups' => ['default']];
        }

        return $output;
    }
}
