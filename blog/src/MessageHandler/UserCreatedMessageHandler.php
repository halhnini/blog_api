<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\MessageHandler;

use App\{
    Message\UserCreatedMessage,
    Repository\UserRepositoryInterface,
    User\UserMailerInterface,
    Utils\ExceptionHelper
};
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class UserCreatedMessageHandler
 */
class UserCreatedMessageHandler implements MessageHandlerInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @var UserMailerInterface
     */
    private UserMailerInterface $userMailer;

    /**
     * UserCreatedMessageHandler constructor.
     *
     * @param LoggerInterface         $logger
     * @param UserRepositoryInterface $userRepository
     * @param UserMailerInterface     $userMailer
     */
    public function __construct(LoggerInterface $logger, UserRepositoryInterface $userRepository, UserMailerInterface $userMailer)
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->userMailer = $userMailer;
    }

    /**
     * @param UserCreatedMessage $message
     *
     * @throws \Throwable
     */
    public function __invoke(UserCreatedMessage $message)
    {
        try {
            $user = $this->userRepository->find($message->getUserId());
            if (!$user) {
                return;
            }

            $this->userMailer->sendWelcomeMail($user);
        } catch (\Throwable $throwable) {
            ExceptionHelper::logException($throwable, $this->logger);

            throw $throwable;
        }
    }
}
