<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Controller\Privates\V1;

use Nelmio\ApiDocBundle\{
    Annotation\Model,
    Annotation\Security
};
use FOS\RestBundle\{
    Controller\AbstractFOSRestController,
    Controller\Annotations as Rest,
    Request\ParamFetcherInterface,
    View\View
};
use Doctrine\ORM\OptimisticLockException;
use App\{
    Post\PostManagerInterface,
    Entity\Post,
    Exception\PostException,
    Model\Error,
    Profile\ProfileManagerInterface,
    Security\Voter\PostVoter,
    Security\Voter\ProfileVoter
};
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

/**
 * Class PostController
 *
 * @Rest\Route("/api/private/v1/posts")
 *
 * @OA\Tag(name="post private v1", description="Private Api to manage post resource.")
 *
 * @Security(name="Bearer")
 *
 * @OA\Response(
 *     response="401",
 *     description="Unauthorized"
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
 * @IsGranted("ROLE_USER")
 */
class PostController extends AbstractFOSRestController
{
    /**
     * @var PostManagerInterface
     */
    private PostManagerInterface $postManager;

    /**
     * @var ProfileManagerInterface
     */
    private ProfileManagerInterface $profileManager;

    /**
     * PostController constructor.
     *
     * @param PostManagerInterface    $postManager
     * @param ProfileManagerInterface $profileManager
     */
    public function __construct(PostManagerInterface $postManager, ProfileManagerInterface $profileManager)
    {
        $this->postManager = $postManager;
        $this->profileManager = $profileManager;
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     *
     * @Rest\Post()
     *
     * @OA\Response(
     *     response="201",
     *     description="post created sucessfully.",
     *     @OA\Schema(ref=@Model(type=Post::class, groups={"post_create", "time_stamp"}))
     * )
     *
     * @OA\RequestBody(
     *     description="add post",
     *     @OA\Schema(
     *         type="object",
     *         @OA\Property(property="title", type="string", description="title"),
     *         @OA\Property(property="content", type="string", description="content"),
     *         @OA\Property(property="profile", type="string", description="Profile Id")
     *     )
     * )
     *
     * @Rest\RequestParam(name="title", requirements=".+", allowBlank=false)
     * @Rest\RequestParam(name="content", requirements=".+", allowBlank=false)
     * @Rest\RequestParam(name="profile", requirements="\d+", allowBlank=false)
     *
     * @Rest\View(serializerGroups={"post_create", "time_stamp"}, statusCode=201)
     *
     * @return View
     *
     * @throws ORMException
     * @throws \InvalidArgumentException
     */
    public function postPost(ParamFetcherInterface $paramFetcher): View
    {
        $profile = $this->profileManager->fetchProfile($paramFetcher->get('profile'));
        $this->denyAccessUnlessGranted(ProfileVoter::CREATE_POST, $profile);
        $post = $this->postManager->createPost($paramFetcher->all());
        $this->postManager->savePost($post, true);

        return $this->view($post, Response::HTTP_CREATED);
    }

    /**
     * @param Post                  $post
     * @param ParamFetcherInterface $paramFetcher
     *
     * @Rest\Patch("/{post}")
     *
     * @OA\Response(
     *     response="200",
     *     description="post updated sucessfully.",
     *     @OA\Schema(ref=@Model(type=Post::class, groups={"post_create", "time_stamp"}))
     * )
     *
     * @OA\RequestBody(
     *     description="update post",
     *     @OA\Schema(
     *         type="object",
     *         @OA\Property(property="title", type="string", description="title"),
     *         @OA\Property(property="content", type="string", description="content"),
     *     )
     * )
     *
     * @Rest\RequestParam(name="title", requirements=".+", allowBlank=false)
     * @Rest\RequestParam(name="content", requirements=".+", allowBlank=false)
     *
     * @Rest\View(serializerGroups={"post_create", "time_stamp"}, statusCode=200)
     *
     * @return View
     *
     * @throws ORMException
     * @throws PostException|\InvalidArgumentException
     */
    public function patchPost(Post $post, ParamFetcherInterface $paramFetcher): View
    {
        $this->denyAccessUnlessGranted(PostVoter::PATCH_ACTION, $post);
        $post = $this->postManager->editPost($post, $paramFetcher->all());
        $this->postManager->savePost($post, true);

        return $this->view($post, Response::HTTP_OK);
    }

    /**
     *
     * @param Post $post
     *
     * @Rest\Delete("/{post}")
     *
     * @OA\Response(
     *     response=204,
     *     description="post has been deleted successful",
     * )
     *
     * @Rest\View(statusCode=204)
     *
     * @return View
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteBook(Post $post): View
    {
        $this->postManager->deletePost($post);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

}
