<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\FrontOffice\BlogController;
use App\Controller\FrontOffice\ConnexionController;
use App\Controller\FrontOffice\Contact;
use App\Controller\FrontOffice\ErrorPageController;
use App\Controller\FrontOffice\HomeController;
use App\Controller\FrontOffice\InscriptionController;
use App\View\View;
use App\Service\Request;
use App\Service\ContactFormValidator;

final class Router
{
    private View $view;

    public function __construct(private Request $request)
    {
        $this->view = new View();
    }

    public function run(): string
    {
        $query  = $this->request->query();
        $action = $query['action'] ?? 'home';
        if ($action === 'home') {
            $contactValidator = new ContactFormValidator();
            $homeController = new HomeController($this->request, $contactValidator, $this->view);
            return $homeController->displayPage();
        } elseif ($action === 'blog') {
            $blogController = new BlogController($this->view);
            return $blogController->displayPage();
        } elseif ($action === 'connexion') {
            $connnexionController = new ConnexionController($this->view);
            return $connnexionController->displayPage();
        } elseif ($action === 'inscription') {
            $loginController = new InscriptionController($this->view);
            return $loginController->displayPage();
        }

        $errorController = new ErrorPageController($this->view);
            return $errorController->displayPage();
    }
}
