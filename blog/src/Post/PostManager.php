<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Post;

use App\Entity\Post;
use App\Entity\User;
use App\Exception\PostException;
use App\Repository\CommentRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Repository\ProfileRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class PostManager
 */
class PostManager implements PostManagerInterface
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var PostRepositoryInterface
     */
    private PostRepositoryInterface $postRepository;

    /**
     * @var ProfileRepositoryInterface
     */
    private ProfileRepositoryInterface $profileRepository;

    /**
     * @var CommentRepositoryInterface
     */
    private CommentRepositoryInterface $commentRepository;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * PostManager constructor.
     *
     * @param FormFactoryInterface       $formFactory
     * @param PostRepositoryInterface    $postRepository
     * @param ProfileRepositoryInterface $profileRepository
     * @param ValidatorInterface         $validator
     * @param EntityManagerInterface     $entityManager
     * @param CommentRepositoryInterface $commentRepository
     */
    public function __construct(FormFactoryInterface $formFactory, PostRepositoryInterface $postRepository, ProfileRepositoryInterface $profileRepository, ValidatorInterface $validator, EntityManagerInterface $entityManager, CommentRepositoryInterface $commentRepository)
    {
        $this->formFactory = $formFactory;
        $this->postRepository = $postRepository;
        $this->profileRepository = $profileRepository;
        $this->commentRepository = $commentRepository;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function createPost(array $postData): Post
    {
        $post = PostFactory::create();
        $postForm = $this->formFactory->create(Post::class, $post);
        $postForm->submit($postData);
        $this->validatePost($post, ['create']);

        return $post;
    }

    /**
     * {@inheritDoc}
     */
    public function savePost(Post $post, bool $flush = false): void
    {
        $this->entityManager->persist($post);
        if (true === $flush) {
            $this->entityManager->flush();
        }

    }

    /**
     * {@inheritDoc}
     */
    public function editPost(Post $post, array $postData): Post
    {
        $postForm = $this->formFactory->create(PostType::class, $post, [
            'method' => Request::METHOD_PATCH,
        ]);
        $postForm->submit($postData);
        $this->validatePost($post, ['update']);

        return $post;
    }

    /**
     * @param Post   $post
     * @param array  $validationGroups
     *
     * @throws PostException
     */
    private function validatePost(Post $post, array $validationGroups): void
    {
        $errors = $this->validator->validate($post, null, $validationGroups);
        if (0 === $errors->count()) {
            return;
        }

        throw new PostException(
            PostException::FORM_VALIDATION_MESSAGE,
            PostException::FORM_VALIDATION_TRACE_CODE,
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fetchPagedPosts(ParamFetcherInterface $paramFetcher): PaginationInterface
    {
        return $this->postRepository->getPagedPosts($paramFetcher->all(true));
    }

    /**
     * {@inheritdoc}
     */
    public function getPagedPostsByConnectedUser(ParamFetcherInterface $paramFetcher, User $user): PaginationInterface
    {
        $profile = $this->profileRepository->findOneBy(['user' => $user]);

        return $this->postRepository->getPagedPostsByConnectedUser($paramFetcher->all(true), $profile);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchPostComments(ParamFetcherInterface $paramFetcher, int $postId): PaginationInterface
    {
        return $this->commentRepository->fetchPostComments($paramFetcher->all(true), $postId);
    }


    /**
     * {@inheritdoc}
     */
    public function deletePost(Post $post): void
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function fetchPost(int $postId): Post
    {
        $post = $this->postRepository->find($postId);
        if (!$post) {
            throw new EntityNotFoundException(
                sprintf('Post not found [%d] !', $postId),
                Response::HTTP_NOT_FOUND
            );
        }

        return $post;
    }
}
