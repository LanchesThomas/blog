<?php

namespace App\Controller\FrontOffice;

use App\View\View;

class LoginController
{
    public function __construct(private View $view)
    {
    }

    public function displayPage(): string
    {

           // $twig = $this->view->render();
            // echo $twig->render('frontoffice/homepage.html.twig', ['name' => 'Fabien']);
            return $this->view->render(['template' => 'login', 'data' => []]);
    }
}
