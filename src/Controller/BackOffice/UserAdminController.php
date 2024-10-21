<?php

declare(strict_types=1);

namespace App\Controller\BackOffice;

use App\View\View;
use App\Model\Repository\UserRepository;
use App\Service\Session;
use App\Service\Request;
use App\Service\Validator;

/**
 * Displays the user administration page by fetching all users from the repository.
 * Renders the 'userAdmin' template with the list of users.
 *
 * @return string The rendered view of the User Admin page.
 */


final class UserAdminController
{
    public function __construct(private View $view, private UserRepository $userRepository, private Session $session, private Request $request, private validator $validator)
    {
    }

    public function displayPage(): string
    {
        $users = $this->userRepository->findAll();

            return $this->view->render(['office' => 'back','template' => 'userAdmin', 'data' => [
                'users' => $users
            ]]);
    }
}
