<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\View\View;

/**
 * Displays the error page by rendering the 'error' template.
 *
 * @return string The rendered view of the Error page.
 */


final class ErrorPageController
{
    public function __construct(private View $view)
    {
    }

    public function displayPage(): string
    {
            return $this->view->render(['office' => 'front', 'template' => 'error', 'data' => []]);
    }
}
