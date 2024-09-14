<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Service\Request;
use App\Model\Repository\UserRepository;
use App\Service\Session;
use App\View\View;

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
                $pseudo = $user->getPseudo();

                if ($user !== null) {
                    $this->session->setUser(['email' => $mail, 'pseudo' => $pseudo ]);
                    $redirect = new \App\Service\RedirectResponse('?action=home');
                    $redirect->send();
                } else {
                    $this->session->addFlashes('error', 'Email ou mot de passe incorrect');
                }
            } catch (\Exception $e) {
                $this->session->addFlashes('error', 'Une erreur est survenue lors de la tentative de connexion.');
            }
        }

        return $this->view->render(['template' => 'connexion', 'data' => []]);
    }
}
