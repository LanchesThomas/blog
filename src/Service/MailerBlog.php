<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

/**
 * Handles sending emails using Symfony Mailer.
 *
 * Constructor:
 * - __construct(array $settings): Initializes the Mailer with SMTP settings provided in the configuration array.
 *
 * Methods:
 * - sendMessage(string $subject, string $content, string $dest): Sends an email with the given subject, content, and destination email address. Returns true if the email is sent successfully, or false if an error occurs.
 */


final class MailerBlog
{
    private Mailer $mailerSF;

    public function __construct(private array $settings)
    {
        $dsn = "smtp://{$this->settings['smtp']}:{$this->settings['smtp_port']}";
        $transport = Transport::fromDsn($dsn);
        $this->mailerSF = new Mailer($transport);
    }

    public function sendMessage(string $subject, string $content, string $dest): bool
    {
        try {
            $email = (new Email())
                ->from($this->settings['from'])
                ->to($dest)
                ->subject($subject)
                ->html($content);

            $this->mailerSF->send($email);
            return true;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return false;
        }
    }
}
