<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Tests\Functinal\Controller\Rest\Publics\V1;

use App\{
    DataFixtures\UserFixtures,
    Tests\Utils\FixtureLoaderTrait,
    Utils\ProfileHelper
};
use Symfony\Component\HttpFoundation\{
    Request,
    Response
};
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Faker;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest
 *
 * @group functionalTest
 */
class UserControllerTest extends WebTestCase
{
    use FixtureLoaderTrait;

    const BASE_RESOURCE_URI = '/api/public/v1/users';

    /**
     * @var KernelBrowser
     */
    private static KernelBrowser $client;

    /**
     * This method is called before each test.
     *
     * Init fixtures loader trait & Purge database & Load needed Fixtures.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->initLoader(
            static::$container->get('doctrine.orm.entity_manager'),
            static::$container->get('doctrine.fixtures.loader')
        );

        $this->loadFixtures([
            new UserFixtures(),
        ]);

        static::$client = static::createClient();
    }

    /**
     * Test Post User.
     *
     * @param string $label
     * @param mixed  $requestData
     * @param int    $expectedStatus
     * @param string $username
     * @param string $password
     *
     * @dataProvider getPostDataProvider
     */
    public function testPostUser(string $label, $requestData, int $expectedStatus): void
    {
        static::$client->request(Request::METHOD_POST, self::BASE_RESOURCE_URI, [], [], ['CONTENT_TYPE' => 'application/json'], \json_encode($requestData));

        $this->assertSame($expectedStatus, static::$client->getResponse()->getStatusCode());
        $assertMethod = sprintf('assertPostUser%s', ucfirst($label));
        if (is_callable([$this, $assertMethod])) {
            $this->{$assertMethod}(static::$client, $requestData);
        }
    }

    /**
     * @return \Generator
     */
    public function getPostDataProvider(): \Generator
    {
        $faker = Faker\Factory::create();

        yield [
            'success',
            [
                'email' => $faker->email,
                'plainPassword' => $faker->password(8, 16),
                'profile' => ProfileHelper::AUTHOR_PROFILE,
            ],
            Response::HTTP_CREATED,
        ];

        yield [
            'badRequest',
            [],
            Response::HTTP_BAD_REQUEST,
        ];

        yield [
            'badRequest',
            [
                'email' => '',
                'plainPassword' => '',
                'profile' => ProfileHelper::AUTHOR_PROFILE,
            ],
            Response::HTTP_BAD_REQUEST,
        ];
    }

    /**
     * @param KernelBrowser $client
     * @param mixed         $requestData
     */
    private function assertPostUserSuccess(KernelBrowser $client, $requestData): void
    {
        $this->assertJson($client->getResponse()->getContent());
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('email', $content);
        $this->assertArrayHasKey('createdAt', $content);
        $this->assertArrayHasKey('updatedAt', $content);
        $this->assertSame($requestData['email'], $content['email']);
    }

    /**
     * @param KernelBrowser $client
     * @param mixed         $requestData
     */
    private function assertPostUserBadRequest(KernelBrowser $client, $requestData): void
    {
        $this->assertJson($client->getResponse()->getContent());
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('title', $content);
        $this->assertArrayHasKey('detail', $content);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $content['status']);
    }
}
