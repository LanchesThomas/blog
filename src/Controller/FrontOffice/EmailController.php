<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Service\MailerBlog;
use Symfony\Component\HttpFoundation\Response;

class EmailController
{
    private MailerBlog $mailer;

    public function __construct(MailerBlog $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(): Response
    {
        $subject = 'Test Email';
        $content = 'This is a test email content';
        $destination = 'lanches.thomas@gmail.com';

        $emailSent = $this->mailer->sendMessage($subject, $content, $destination);

        return new Response($emailSent ? 'Email sent successfully' : 'Failed to send email');
    }
}
