<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\View\View;

final class ErrorPageController
{
    public function __construct(private View $view)
    {
    }

    public function displayPage(): string
    {

           // $twig = $this->view->render();
            // echo $twig->render('frontoffice/homepage.html.twig', ['name' => 'Fabien']);
            return $this->view->render(['office' => 'front', 'template' => 'error', 'data' => []]);
    }
}
