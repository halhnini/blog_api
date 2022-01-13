<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\User;

use App\{
    Entity\User,
    Mailer\AbstractMailer,
    Utils\ExceptionHelper
};
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

/**
 * Class UserMailer
 */
class UserMailer extends AbstractMailer implements UserMailerInterface
{
    const WELCOME_MAIL_TEMPLATE_FOLDER = 'user/welcome';

    /**
     * {@inheritDoc}
     */
    public function sendWelcomeMail(User $user): bool
    {
        try {
            $email = (new TemplatedEmail())
                ->subject($this->translateSubject('mailer.user.subject.welcome'))
                ->from($this->getSupportAddress())
                ->to($this->getUserAddress($user))
                ->htmlTemplate($this->getTemplateFileName(self::WELCOME_MAIL_TEMPLATE_FOLDER, 'en'))
                ->context(['user' => $user])
            ;

            $this->mailer->send($email);

            return true;
        } catch (\Throwable $throwable) {
            ExceptionHelper::logException($throwable, $this->logger);

            return false;
        }
    }

    /**
     * Get user email address.
     *
     * @param User $user
     *
     * @return Address
     */
    private function getUserAddress(User $user): Address
    {
        return new Address($user->getEmail());
    }
}
