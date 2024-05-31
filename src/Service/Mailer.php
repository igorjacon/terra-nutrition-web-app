<?php

namespace App\Service;

use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Scheb\TwoFactorBundle\Mailer\AuthCodeMailerInterface;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;

class Mailer implements AuthCodeMailerInterface
{
    /** @var MailerInterface */
    private $mailer;

    /** @var UrlGeneratorInterface */
    private $router;

    /** @var \Twig\Environment */
    private $twig;

    /** @var TranslatorInterface */
    private $translator;

    /** @var array|null */
    private $emailSender;

    private LoggerInterface $logger;
    private UserRepository $userRepository;

    /**
     * Mailer constructor.
     * @param MailerInterface $mailer
     * @param UrlGeneratorInterface $router
     * @param \Twig\Environment $twig
     * @param TranslatorInterface $translator
     * @param array|null $emailSender
     */
    public function __construct(MailerInterface $mailer,
                                UrlGeneratorInterface $router,
                                \Twig\Environment $twig,
                                TranslatorInterface $translator,
                                LoggerInterface $logger,
                                UserRepository $userRepository,
                                ?array $emailSender)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->translator = $translator;
        $this->emailSender = $emailSender;
    }

    public function sendAuthCode(TwoFactorInterface $user): void
    {
        if (is_array($this->emailSender)) {
            $senderEmail = array_key_first($this->emailSender);
            $senderLabel = $this->emailSender[$senderEmail];
            $fromEmail = new Address($senderEmail, $senderLabel);
        } else {
            $fromEmail = $this->emailSender;
        }
        $this->sendEmail('email.auth_code.subject',
            $fromEmail,
            $user->getEmail(),
            'email/auth_code.html.twig',
            ['authCode' => $user->getEmailAuthCode(),]
        );
    }

    public function sendEmail($subject, $fromEmail, $toEmail, $templateName, $templateParams, array $cc = [],
                              array $attachments = [], $replyTo = null, array $bcc = []): void
    {
        if (null === $fromEmail) {
            $senderEmail = array_key_first($this->emailSender);
            $senderLabel = $this->emailSender[$senderEmail];
            $fromEmail = new Address($senderEmail, $senderLabel);
        }
        if (is_array($fromEmail)) {
            $senderEmail = array_key_first($this->emailSender);
            $senderLabel = $this->emailSender[$senderEmail];
            $fromEmail = new Address($senderEmail, $senderLabel);
        }
        $subjectParams = [];

        if(is_array($subject)){
            if(isset($subject[1])) {
                $subjectParams = $subject[1];
            }
            if(isset($subject[2])){
                $translatingSubject = $subject[2];
            }
            $subject = $this->translator->trans($subject[0], $subjectParams, 'messages');
        }

        $user = $this->userRepository->findOneByEmail($toEmail);

//        $locale = null;
//        if ($user and $user->getPreferredLanguage()){
//            $locale = strtolower($user->getPreferredLanguage()->getCode());
//        }
        $email = (new TemplatedEmail())
            ->subject($subject)
            ->from($fromEmail)
            ->sender($fromEmail)
            ->htmlTemplate($templateName)
            ->context(
                array_merge($templateParams, [
                    'subject' => $subject
                ])
            );

        if (is_array($toEmail)) {
            foreach ($toEmail as $to) {
                $email->addTo($to);
            }
        } else {
            if ($toEmail) {
                $email->to($toEmail);
            }
        }

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
        }
    }
}