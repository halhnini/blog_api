<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Comment;

use App\Entity\Comment;
use App\Entity\Post;
use App\Exception\CommentException;
use App\Exception\PostException;
use App\Post\PostManagerInterface;
use App\Repository\CommentRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CommentManager
 */
class CommentManager implements CommentManagerInterface
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var CommentRepositoryInterface
     */
    private CommentRepositoryInterface $commentRepository;

    /**
     * @var PostManagerInterface
     */
    private PostManagerInterface $postManager;

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
     * @param FormFactoryInterface         $formFactory
     * @param CommentRepositoryInterface   $commentRepository
     * @param PostManagerInterface         $postManager
     * @param ValidatorInterface           $validator
     * @param EntityManagerInterface       $entityManager
     */
    public function __construct(FormFactoryInterface $formFactory, CommentRepositoryInterface $commentRepository, PostManagerInterface $postManager, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->formFactory = $formFactory;
        $this->commentRepository = $commentRepository;
        $this->postManager = $postManager;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function createComment(array $commentData): Comment
    {
        $comment = CommentFactory::create();
        $commentForm = $this->formFactory->create(Comment::class, $comment);
        $commentForm->submit($commentData);
        $post = $this->postManager->fetchPost($commentData['post']);
        $comment->setPost($post);
        $this->validateComment($comment, ['create']);

        return $comment;
    }

    /**
     * {@inheritDoc}
     */
    public function saveComment(Comment $comment, bool $flush = false): void
    {
        $this->entityManager->persist($comment);
        if (true === $flush) {
            $this->entityManager->flush();
        }

    }

    /**
     * {@inheritDoc}
     */
    public function editComment(Comment $comment, array $commentData): Comment
    {
        $postForm = $this->formFactory->create(CommentType::class, $comment, [
            'method' => Request::METHOD_PATCH,
        ]);
        $postForm->submit($commentData);
        $this->validateComment($comment, ['update']);

        return $comment;
    }

    /**
     * @param Comment   $comment
     * @param array     $validationGroups
     *
     * @throws CommentException
     */
    private function validateComment(Comment $comment, array $validationGroups): void
    {
        $errors = $this->validator->validate($comment, null, $validationGroups);
        if (0 === $errors->count()) {
            return;
        }

        throw new CommentException(
            CommentException::FORM_VALIDATION_MESSAGE,
            CommentException::FORM_VALIDATION_TRACE_CODE,
            Response::HTTP_BAD_REQUEST
        );
    }
}
