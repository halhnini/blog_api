<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Tests\Unit\Entity;

use Faker\{
    Factory,
    Generator
};
use App\{
    Entity\AbstractProfile,
    Entity\User,
    Profile\ProfileFactory,
    Utils\GenderHelper
};
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractProfileTest
 */
abstract class AbstractProfileTest extends TestCase
{
    /**
     * @var AbstractProfile
     */
    protected $profileInstance;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * Test Accessor.
     *
     * @throws \InvalidArgumentException
     */
    public function testAccessor(): void
    {
        $this->faker = Factory::create();
        $this->profileInstance = ProfileFactory::create($this->getProfileType());
        $user = $this->prophesize(User::class)->reveal();
        $phone = $this->faker->e164PhoneNumber;
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $gender = $this->faker->randomKey(GenderHelper::GENDER_CODE_LABEL);
        $valid = $this->faker->boolean;

        // Assert setters
        $this->assertSame($this->profileInstance, $this->profileInstance->setUser($user));
        $this->assertSame($this->profileInstance, $this->profileInstance->setGender($gender));
        $this->assertSame($this->profileInstance, $this->profileInstance->setPhone($phone));
        $this->assertSame($this->profileInstance, $this->profileInstance->setFirstName($firstName));
        $this->assertSame($this->profileInstance, $this->profileInstance->setLastName($lastName));
        $this->assertSame($this->profileInstance, $this->profileInstance->setValid($valid));
        // Assert Getters
        $this->assertNull($this->profileInstance->getId());
        $this->assertSame($user, $this->profileInstance->getUser());
        $this->assertSame($gender, $this->profileInstance->getGender());
        $this->assertSame($phone, $this->profileInstance->getPhone());
        $this->assertSame($firstName, $this->profileInstance->getFirstName());
        $this->assertSame($lastName, $this->profileInstance->getLastName());
        $this->assertSame($valid, $this->profileInstance->isValid());
    }

    /**
     * @return string
     */
    abstract protected function getProfileType(): string;
}
