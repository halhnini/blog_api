<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Message;

/**
 * Class UserCreatedMessage
 */
class UserCreatedMessage implements AsyncMessageInterface
{
    const BUS_ROUTING_KEY = 'user.create';

    /**
     * @var int
     */
    private $userId;

    /**
     * UserCreatedMessage constructor.
     *
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
