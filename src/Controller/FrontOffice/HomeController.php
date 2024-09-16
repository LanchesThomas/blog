<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Service\Request;
use App\Service\Validator;
use App\Service\ContactFormValidator;
use App\View\View;
use App\Service\Session;
use App\Service\RedirectResponse;
use App\Service\MailerBlog;

final class HomeController
{
    public function __construct(private Request $request, private ContactFormValidator $contactValidator, private View $view, private Session $session, private MailerBlog $mailer)
    {
    }

    public function displayPage(): string
    {
        $oldInputs = [];

        if ($this->request->getMethod() === 'POST') {
            $firstname = $this->request->getRequestData('firstname') ?? '';
            $lastname = $this->request->getRequestData('lastname') ?? '';
            $email = $this->request->getRequestData('email') ?? '';
            $message = $this->request->getRequestData('message') ?? '';


            if ($this->contactValidator->isValid($firstname, $lastname, $email, $message)) {
                $subject = 'New Contact Message from ' . $firstname . ' ' . $lastname;
                $content = sprintf(
                    "Name: %s %s\nEmail: %s\n\nMessage:\n%s",
                    $firstname,
                    $lastname,
                    $email,
                    $message
                );
                $destination = 'lanches.thomas@gmail.com';

                $content = $this->view->render(['office' => 'front',  'template' => 'email', 'data' => ['content' => $content]]);
                $emailSent = $this->mailer->sendMessage($subject, $content, $destination);

                if ($emailSent) {
                    $this->session->addFlashes('success', 'Votre message a été envoyé avec succès.');
                    $this->session->clearOldInput();
                } else {
                    $this->session->addFlashes('error', 'Échec de l\'envoi du message. Veuillez réessayer plus tard.');
                }



                $redirect = new \App\Service\RedirectResponse('/home');
                $redirect->send();
            } else {
                $this->session->addFlashes('error', $this->contactValidator->getErrorMessage());
                $oldInputs = ['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'message' => $message];
            }
        }

            return $this->view->render(['office' => 'front',  'template' => 'home', 'data' => [
                    'oldInputs' => $oldInputs
                ]]);
    }
}
