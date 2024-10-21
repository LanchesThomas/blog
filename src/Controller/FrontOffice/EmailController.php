<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Service\MailerBlog;

/**
 * Initializes the EmailController with a MailerBlog instance.
 *
 * @param MailerBlog $mailer The mailer service used to send emails.
 */


class EmailController
{
    public function __construct(private MailerBlog $mailer)
    {
        $this->mailer = $mailer;
    }
}
