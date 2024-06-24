<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

final class Contact
{
    public function execute()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (is_string($_POST['firstname'])) {
                $firstname = strip_tags($_POST['firstname']);
            }

            if (is_string($_POST['lastname'])) {
                $lastname = strip_tags($_POST['lastname']);
            }

            if (is_string($_POST['email'])) {
                $email = strip_tags($_POST['email']);
            }

            if (is_string($_POST['message'])) {
                $message = strip_tags($_POST['message']);
            }
        }
    }
}
