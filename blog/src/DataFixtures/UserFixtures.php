<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\DataFixtures;

use Doctrine\{
    Bundle\FixturesBundle\Fixture,
    Common\DataFixtures\OrderedFixtureInterface,
    Persistence\ObjectManager
};
use App\{
    User\UserFactory,
    Utils\SecurityHelper
};

/**
 * Class UserFixtures
 */
class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    const USER_FIXTURES_REFERENCE_PREFIX = 'fake_user';
    const PASSWORD = 'fake_password';
    const USERS_DATA = [
        'author' => [
            'email' => 'author_user_%d@email.com',
            'role' => SecurityHelper::ROLE_AUTHOR,
        ],
        'editor' => [
            'email' => 'editor_user_%d@email.com',
            'role' => SecurityHelper::ROLE_EDITOR,
        ],
        'contributor' => [
            'email' => 'buyer_user_%d@email.com',
            'role' => SecurityHelper::ROLE_CONTRIBUTOR,
        ],
        'user' => [
            'email' => 'user_%d@email.com',
            'role' => SecurityHelper::ROLE_USER,
        ],
    ];

    /**
     * @var ObjectManager
     */
    private ObjectManager $manager;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        foreach (self::USERS_DATA as $index => $userData) {
            $this->loadRoleUsers($index, $userData);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * @param string $profile
     * @param array  $userData
     * @param int    $usersCount
     */
    private function loadRoleUsers(string $profile, array $userData, int $usersCount = 4): void
    {
        for ($i = 1; $i <= $usersCount; $i++) {
            $user = UserFactory::create();
            $user
                ->setEmail(sprintf($userData['email'], $i))
                ->setPlainPassword(self::PASSWORD)
                ->addRole($userData['role'])
                ->setActive(true)
                ->setToken(uuid_create(UUID_TYPE_RANDOM));
            ;

            $this->addReference(
                sprintf('%s_%s_%d', self::USER_FIXTURES_REFERENCE_PREFIX, $profile, $i),
                $user
            );

            $this->manager->persist($user);
        }
    }
}
