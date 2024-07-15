<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Service\Request;
use App\Service\Validator;
use App\Service\ContactFormValidator;
use App\View\View;
use App\Service\Session;
use App\Service\RedirectResponse;

final class HomeController
{
    public function __construct(private Request $request, private ContactFormValidator $contactValidator, private View $view, private Session $session)
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
                $this->session->addFlashes('success', 'Votre message a été envoyé avec succès.');
                $this->session->clearOldInput();


                $redirect = new \App\Service\RedirectResponse('/home');
                $redirect->send();
            } else {
                $this->session->addFlashes('error', $this->contactValidator->getErrorMessage());
                $oldInputs = ['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'message' => $message];
            }
        }

            return $this->view->render(['template' => 'home', 'data' => [
                    'oldInputs' => $oldInputs
                ]]);
    }
}
