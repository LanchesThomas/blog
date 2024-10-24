<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Service\Request;
use App\Model\Repository\UserRepository;
use App\Service\Session;
use App\View\View;

/**
 * Handles user login by processing form submission (POST request).
 * Validates the provided email and password, sets the user session if authentication is successful, and redirects to the home page.
 * If login fails, adds error messages to the session and renders the login form.
 *
 * @return string The rendered view of the Connexion (login) page.
 */


final class ConnexionController
{
    public function __construct(private View $view, private Request $request, private UserRepository $userRepository, private Session $session)
    {
    }

    public function displayPage(): string
    {
        if ($this->request->getMethod() === 'POST') {
            $mail = $this->request->getRequestData('mail');
            $password = $this->request->getRequestData('password');

            try {
                $user = $this->userRepository->findOneBy(['mail' => $mail, 'password' => $password]);


                if ($user !== null) {
                    $pseudo = $user->getPseudo();
                    $userId = $user->getId();
                    $userRole = $user->getRole();
                    $this->session->setUser(['email' => $mail, 'pseudo' => $pseudo, 'userId' => $userId, 'userRole' => $userRole ]);
                    $redirect = new \App\Service\RedirectResponse('?action=home');
                    $redirect->send();
                } else {
                    $this->session->addFlashes('error', 'Email ou mot de passe incorrect');
                }
            } catch (\Exception $e) {
                $this->session->addFlashes('error', 'Une erreur est survenue lors de la tentative de connexion.');
            }
        }

        return $this->view->render(['office' => 'front', 'template' => 'connexion', 'data' => []]);
    }
}
