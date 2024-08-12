<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailerBlog
{
    private Environment $twig;
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
