<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Controller\Privates\V1;

use FOS\RestBundle\{Controller\AbstractFOSRestController,
    Controller\Annotations as Rest,
    Request\ParamFetcherInterface,
    View\View};
use Doctrine\ORM\Exception\ORMException;
use Nelmio\ApiDocBundle\Annotation\{
    Model,
    Security
};
use App\{Exception\ProfileDomainException,
    Post\PostManagerInterface,
    Profile\ProfileData,
    Profile\ProfileManagerInterface,
    Entity\AbstractProfile,
    Entity\Post,
    Model\Error,
    Security\Voter\UserVoter,
    User\UserManagerInterface,
    Utils\ProfileHelper,
    Utils\GenderHelper
};
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as ExtraSecurity;
use Doctrine\ORM\EntityNotFoundException;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProfileController
 *
 * @Rest\Route("/api/private/v1/profiles")
 *
 * @OA\Tag(name="App profiles private v1", description="Private Api to manage Profile.")
 *
 * @Security(name="Bearer")
 *
 * @OA\Response(
 *     response="401",
 *     description="Unauthorized",
 *     @OA\Schema(ref=@Model(type=Error::class))
 * )
 * @OA\Response(
 *     response="403",
 *     description="Access denied.",
 *     @OA\Schema(ref=@Model(type=Error::class))
 * )
 * @OA\Response(
 *     response="400",
 *     description="Bad request",
 *     @OA\Schema(ref=@Model(type=Error::class))
 * )
 *
 * @ExtraSecurity("is_granted('ROLE_AUTHOR') or is_granted('ROLE_EDITOR') or is_granted('ROLE_CONTRIBUTOR') or is_granted('ROLE_SUBSCRIBER') or is_granted('ROLE_ADMIN')")
 */
class ProfileController extends AbstractFOSRestController
{
    /**
     * @var UserManagerInterface
     */
    protected UserManagerInterface $userManager;

    /**
     * @var ProfileManagerInterface
     */
    protected ProfileManagerInterface $profileManager;

    /**
     * @var PostManagerInterface
     */
    protected PostManagerInterface $postManager;

    /**
     * ProfileController constructor.
     *
     * @param UserManagerInterface    $userManager
     * @param ProfileManagerInterface $profileManager
     * @param PostManagerInterface    $postManager
     */
    public function __construct(UserManagerInterface $userManager, ProfileManagerInterface $profileManager, PostManagerInterface $postManager)
    {
        $this->userManager = $userManager;
        $this->profileManager = $profileManager;
        $this->postManager = $postManager;
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     *
     * @Rest\Post()
     *
     * @OA\Response(
     *     response="201",
     *     description="profile created sucessfully.",
     *     @OA\Schema(ref=@Model(type=AbstractProfile::class, groups={"profile_create", "time_stamp"}))
     * )
     *
     * @OA\RequestBody (
     *     description="add profile",
     *     @OA\Schema(
     *         type="object",
     *         @OA\Property(property="user", type="integer", description="The user ID."),
     *         @OA\Property(property="firstName", type="string", description="Profile firstName."),
     *         @OA\Property(property="lastName", type="string", description="Profile lastName."),
     *         @OA\Property(property="phone", type="string", pattern="(\+212|0)([ \-_/]*)(\d[ \-_/]*){9}", description="Profile phone."),
     *         @OA\Property(property="gender", type="string", description="Profile gender.", enum=GenderHelper::GENDER_CODE_LABEL),
     *         @OA\Property(property="type", type="string", description="Profile type.", enum=ProfileHelper::PROFILE_TYPES),
     *     )
     * )
     *
     * @Rest\RequestParam(name="user", requirements="\d+", allowBlank=false)
     * @Rest\RequestParam(name="firstName", requirements=".+", allowBlank=false)
     * @Rest\RequestParam(name="lastName", requirements=".+", allowBlank=false)
     * @Rest\RequestParam(name="gender", allowBlank=false, requirements="MISTER|MADAME|MISS")
     * @Rest\RequestParam(name="phone", requirements="(\+212|0)([ \-_/]*)(\d[ \-_/]*){9}", allowBlank=false)
     * @Rest\RequestParam(name="type", requirements="AUTHOR|EDITOR|CONTRIBUTOR|SUBSCRIBER|ADMINISTRATOR", allowBlank=false)
     *
     * @Rest\View(serializerGroups={"profile_create", "time_stamp"}, statusCode=201)
     *
     * @return View
     *
     * @throws ProfileDomainException
     * @throws ORMException
     * @throws \InvalidArgumentException
     */
    public function postProfile(ParamFetcherInterface $paramFetcher): View
    {
        $user = $this->userManager->fetchUser($paramFetcher->get('user'));

        $this->denyAccessUnlessGranted(UserVoter::CREATE_PROFILE_ACTION, $user);

        $profile = $this->profileManager->addProfile(
            new ProfileData($user, $paramFetcher->get('type'), $paramFetcher->all())
        );

        return $this->view($profile, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("/my-profile")
     *
     * @OA\Response(
     *     response="200",
     *     description="Return the connected user profile",
     *     @OA\Schema(ref=@Model(type=AbstractProfile::class, groups={"profile_detail", "time_stamp"}))
     * )
     *
     * @Rest\View(serializerGroups={"profile_detail", "time_stamp"}, statusCode=200)
     *
     * @return View
     *
     * @throws EntityNotFoundException
     */
    public function getConnectedUserProfile(): View
    {
        return $this->view(
            $this->profileManager->fetchUserProfile($this->getUser()->getId()),
            Response::HTTP_OK
        );
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     *
     * @Rest\Get("/my-profile/posts")
     *
     * @OA\Response(
     *     response="200",
     *     description="Return the connected user posts",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(
     *             ref=@Model(
     *                 type=Post::class,
     *                 groups={"post_list", "paginator", "paginator_custom_parameters"}
     *             )
     *         )
     *     )
     * )
     *
     * @Rest\QueryParam(name="page", description="Current page", strict=true, nullable=true, default="1")
     * @Rest\QueryParam(name="limit", description="Limit elements per page", strict=true, nullable=true, default="10")
     *
     * @Rest\View(serializerGroups={"profile_detail", "time_stamp"}, statusCode=200)
     *
     * @return View
     */
    public function getConnectedProfilePosts(ParamFetcherInterface $paramFetcher): View
    {
        return $this->view(
            $this->postManager->getPagedPostsByConnectedUser($paramFetcher, $this->getUser()),
            Response::HTTP_OK
        );
    }
}
