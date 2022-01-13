<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Mailer;

use Symfony\Component\{
    Mailer\MailerInterface,
    Mime\Address,
    DependencyInjection\ParameterBag\ParameterBagInterface
};
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AbstractMailer
 */
abstract class AbstractMailer
{
    /**
     * @var MailerInterface
     */
    protected MailerInterface $mailer;

    /**
     * @var TranslatorInterface
     */
    protected TranslatorInterface $translator;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var ParameterBagInterface
     */
    protected ParameterBagInterface $parameterBag;

    /**
     * AbstractMailer constructor.
     *
     * @param MailerInterface       $mailer
     * @param TranslatorInterface   $translator
     * @param LoggerInterface       $logger
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(MailerInterface $mailer, TranslatorInterface $translator, LoggerInterface $logger, ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->logger = $logger;
        $this->parameterBag = $parameterBag;
    }

    /**
     * Get support email address.
     *
     * @return Address
     */
    protected function getSupportAddress(): Address
    {
        return new Address(
            $this->parameterBag->get('support_email'),
            $this->parameterBag->get('support_name'),
        );
    }

    /**
     * @param string $subject
     *
     * @return string
     */
    protected function translateSubject(string $subject): string
    {
        return $this->translator->trans($subject, [], 'mailer');
    }

    /**
     * @param string $templateFolder
     * @param string $locale
     *
     * @return string
     */
    protected function getTemplateFileName(string $templateFolder, string $locale): string
    {
        return sprintf('@mailer/%s/%s.html.twig', $templateFolder, $locale);
    }
}
