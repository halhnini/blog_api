<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Post;

use App\{Entity\Post, Entity\User, Exception\PostException};
use Doctrine\ORM\{EntityNotFoundException, Exception\ORMException, OptimisticLockException};
use FOS\RestBundle\Request\ParamFetcherInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface PostManagerInterface
 */
interface PostManagerInterface
{
    /**
     * Create & save a new post to database.
     *
     * @param array $postData
     *
     * @return Post
     */
    public function createPost(array $postData): Post;

    /**
     * @param Post $post
     * @param bool $flush
     *
     * @throws ORMException
     * @throws \InvalidArgumentException
     */
    public function savePost(Post $post, bool $flush = false): void;

    /**
     * @param Post  $post
     * @param array $postData
     *
     * @return Post
     *
     * @throws PostException
     */
    public function editPost(Post $post, array $postData): Post;

    /**
     * @param Post $post
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deletePost(Post $post): void;

    /**
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return PaginationInterface
     *
     * @throws ORMException
     */
    public function fetchPagedPosts(ParamFetcherInterface $paramFetcher): PaginationInterface;

    /**
     * @param ParamFetcherInterface $paramFetcher
     * @param User                  $user
     *
     * @return PaginationInterface
     *
     * @throws ORMException
     */
    public function getPagedPostsByConnectedUser(ParamFetcherInterface $paramFetcher, User $user): PaginationInterface;

    /**
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return PaginationInterface
     *
     * @throws ORMException
     */
    public function fetchPostComments(ParamFetcherInterface $paramFetcher, int $postId): PaginationInterface;

    /**
     * @param int $postId
     *
     * @return Post
     *
     * @throws EntityNotFoundException
     */
    public function fetchPost(int $postId): Post;
}
