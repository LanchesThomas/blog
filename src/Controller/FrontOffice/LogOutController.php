<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Service\Session;

/**
 * Logs out the current user by clearing the user session and redirecting to the homepage.
 *
 * @return void
 */


final class LogOutController
{
    public function __construct(private Session $session)
    {
    }

    public function logOut(): void
    {
        $this->session->clearUser();
        $redirect = new \App\Service\RedirectResponse('?action=home');
        $redirect->send();
    }
}
