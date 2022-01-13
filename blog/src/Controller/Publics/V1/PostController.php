<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Controller\Publics\V1;

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
use App\{
    Post\PostManagerInterface,
    Entity\Post,
    Entity\Comment,
    Profile\ProfileManagerInterface
};

use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

/**
 * Class PostController
 *
 * @Rest\Route("/api/public/v1/posts")
 *
 * @OA\Tag(name="post public v1", description="Public Api to manage post resource.")
 *
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
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @Rest\Get()
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the posts list",
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
     * @Rest\QueryParam(name="page", description="Current page", strict=true, nullable=true, default="1")
     * @Rest\QueryParam(name="limit", description="Limit elements per page", strict=true, nullable=true, default="10")
     *
     * @Rest\View(serializerGroups={"post_list", "paginator", "paginator_custom_parameters"})
     *
     * @return View
     */
    public function getPosts(ParamFetcherInterface $paramFetcher): View
    {
        return $this->view(
            $this->postManager->fetchPagedPosts($paramFetcher),
            Response::HTTP_OK
        );
    }

     /**
     * @param Post                  $post
     * @param ParamFetcherInterface $paramFetcher
     *
     * @Rest\Get("/posts/{post}/comments")
     *
     * @OA\Response(
     *     response="200",
     *     description="Return all post comments",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(
     *             ref=@Model(
     *                 type=Comment::class,
     *                 groups={"comment_list", "paginator", "paginator_custom_parameters"}
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
    public function getPostComments(Post $post, ParamFetcherInterface $paramFetcher): View
    {
        return $this->view(
            $this->postManager->fetchPostComments($paramFetcher, $post->getId()),
            Response::HTTP_OK
        );
    }
}
