<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Controller\Publics\V1;

use FOS\RestBundle\{
    Controller\AbstractFOSRestController,
    Controller\Annotations as Rest,
    Request\ParamFetcherInterface,
    View\View
};
use App\{Exception\BlogDomainException,
    Message\UserCreatedMessage,
    Model\Error,
    User\UserManagerInterface,
    Entity\User,
    Utils\ExceptionHelper,
    Utils\ProfileHelper,
    User\UserType
};
use Nelmio\ApiDocBundle\Annotation\{
    Model,
    Security
};
use Symfony\Component\{
    HttpFoundation\Response,
    Validator\Constraints,
    Messenger\Transport\AmqpExt\AmqpStamp,
    Messenger\MessageBusInterface
};
use Psr\Log\LoggerInterface;
use Doctrine\ORM\ORMException;
use OpenApi\Annotations as SWG;

/**
 * Class UserController
 *
 * @Rest\Route("/api/public/v1/users")
 *
 * @SWG\Tag(name="users public v1", description="Public Api to manage User resource.")
 *
 */
class UserController extends AbstractFOSRestController
{
    /**
     * @var UserManagerInterface
     */
    protected UserManagerInterface $userManager;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * UserController constructor.
     *
     * @param UserManagerInterface $userManager
     * @param LoggerInterface      $logger
     */
    public function __construct(UserManagerInterface $userManager, LoggerInterface $logger)
    {
        $this->userManager = $userManager;
        $this->logger = $logger;
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     * @param MessageBusInterface   $bus
     *
     * @Rest\Post()
     *
     * @SWG\Response(
     *     response="201",
     *     description="User created sucessfully.",
     *     @SWG\Schema(ref=@Model(type=User::class, groups={"user_create", "time_stamp"}))
     * )
     *
     * @SWG\RequestBody(
     *     description="add user",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="email", type="string", description="User email."),
     *         @SWG\Property(property="plainPassword", type="string", description="User password"),
     *         @SWG\Property(property="profile", type="string", description="User profile", enum={ProfileHelper::AUTHOR_PROFILE, ProfileHelper::EDITOR_PROFILE, ProfileHelper::CONTRIBUTOR_PROFILE, ProfileHelper::SUBSCRIBER_PROFILE})
     *     )
     * )
     *
     * @Rest\RequestParam(name="email", requirements=@Constraints\Email, allowBlank=false)
     * @Rest\RequestParam(name="plainPassword", requirements=".+", allowBlank=false)
     * @Rest\RequestParam(name="profile", requirements="AUTHOR|EDITOR|CONTRIBUTOR|SUBSCRIBER", allowBlank=false)
     *
     * @Rest\View(serializerGroups={"user_create", "time_stamp"}, statusCode=201)
     *
     * @return View
     *
     * @throws BlogDomainException
     * @throws \InvalidArgumentException
     * @throws ORMException
     */
    public function postUser(ParamFetcherInterface $paramFetcher, MessageBusInterface $bus): View
    {
        $user = $this->userManager->createUser(
            $paramFetcher->all(),
            $paramFetcher->get('profile')
        );

        try {
            $bus->dispatch(new UserCreatedMessage($user->getId()), [
                UserCreatedMessage::BUS_ROUTING_KEY,
            ]);
        } catch (\Throwable $throwable) {
            ExceptionHelper::logException($throwable, $this->logger);
        }

        return $this->view($user, Response::HTTP_CREATED);
    }
}
