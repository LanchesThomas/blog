<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\FrontOffice\BlogController;
use App\Controller\FrontOffice\ConnexionController;
use App\Controller\FrontOffice\HomeController;
use App\Controller\FrontOffice\LoginController;
use App\View\View;

final class Router
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function run()
    {
        $action = $_GET['action'] ?? 'home';
        if ($action === 'home') {
            $homeController = new HomeController($this->view);
            return $homeController->displayPage();
        }

        if ($action === 'blog') {
            $blogController = new BlogController($this->view);
            return $blogController->displayPage();
        }

        if ($action === 'connexion') {
            $connnexionController = new ConnexionController($this->view);
            return $connnexionController->displayPage();
        }

        if ($action === 'login') {
            $loginController = new LoginController($this->view);
            return $loginController->displayPage();
        }
    }
}
