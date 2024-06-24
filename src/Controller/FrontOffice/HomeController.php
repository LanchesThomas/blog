<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Service\Request;
use App\Service\Validator;
use App\Service\ContactFormValidator;
use App\View\View;

final class HomeController
{
    public function __construct(private Request $request, private ContactFormValidator $contactValidator, private View $view)
    {
    }

    public function displayPage(): string
    {

        if ($this->request->getMethod() === 'POST') {
            $firstname = $this->request->getRequestData('firstname') ?? '';
            $lastname = $this->request->getRequestData('lastname') ?? '';
            $email = $this->request->getRequestData('email') ?? '';
            $message = $this->request->getRequestData('message') ?? '';

            if ($this->contactValidator->isValid($firstname, $lastname, $email, $message)) {
                echo "All inputs are valid.\n";
                die();
            } else {
                echo "There are invalid inputs.\n";
                die();
            }
        }



            return $this->view->render(['template' => 'home', 'data' => []]);
    }
}
