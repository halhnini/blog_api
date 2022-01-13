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
use App\{Comment\CommentManagerInterface,
    Entity\Comment,
    Exception\PostException,
    Model\Error,
    Profile\ProfileManagerInterface,
    Security\Voter\PostVoter,
    Security\Voter\ProfileVoter};
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

/**
 * Class CommentController
 *
 * @Rest\Route("/api/private/v1/comment")
 *
 * @OA\Tag(name="comment public v1", description="Public Api to manage comment resource.")
 *
 */
class CommentController extends AbstractFOSRestController
{
    /**
     * @var CommentManagerInterface
     */
    private CommentManagerInterface $commentManager;

    /**
     * @var ProfileManagerInterface
     */
    private ProfileManagerInterface $profileManager;

    /**
     * CommentController constructor.
     *
     * @param CommentManagerInterface $commentManager
     * @param ProfileManagerInterface $profileManager
     */
    public function __construct(CommentManagerInterface $commentManager, ProfileManagerInterface $profileManager)
    {
        $this->commentManager = $commentManager;
        $this->profileManager = $profileManager;
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     *
     * @Rest\Post()
     *
     * @OA\Response(
     *     response="201",
     *     description="comment created sucessfully.",
     *     @OA\Schema(ref=@Model(type=Comment::class, groups={"comment_create", "time_stamp"}))
     * )
     *
     * @OA\RequestBody(
     *     description="add comment",
     *     @OA\Schema(
     *         type="object",
     *         @OA\Property(property="content", type="string", description="content"),
     *         @OA\Property(property="post", type="string", description="post Id")
     *     )
     * )
     *
     * @Rest\RequestParam(name="content", requirements=".+", allowBlank=false)
     * @Rest\RequestParam(name="post", requirements="\d+", allowBlank=false)
     *
     * @Rest\View(serializerGroups={"comment_create", "time_stamp"}, statusCode=201)
     *
     * @return View
     *
     * @throws ORMException
     * @throws \InvalidArgumentException
     */
    public function postComment(ParamFetcherInterface $paramFetcher): View
    {
        $comment = $this->commentManager->createComment($paramFetcher->all());
        $this->commentManager->saveComment($comment, true);

        return $this->view($comment, Response::HTTP_CREATED);
    }
}
