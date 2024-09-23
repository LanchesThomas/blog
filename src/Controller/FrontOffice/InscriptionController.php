<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\Service\Request;
use App\Model\Repository\UserRepository;
use App\Service\Session;
use App\View\View;

final class InscriptionController
{
    public function __construct(
        private View $view,
        private Request $request,
        private UserRepository $userRepository,
        private Session $session
    ) {
    }

    public function displayPage(): string
    {
        if ($this->request->getMethod() === 'POST') {
            $mail = $this->request->getRequestData('mail');
            $pseudo = $this->request->getRequestData('pseudo');
            $password = $this->request->getRequestData('password');
            $checkPassword = $this->request->getRequestData('check-password');

            if ($mail == $this->userRepository->findOneBy(['mail' => $mail])) {
                $this->session->addFlashes('error', 'Cet email est déjà utilisé');
            } elseif ($pseudo == $this->userRepository->findOneBy(['pseudo' => $pseudo])) {
                $this->session->addFlashes('error', 'Ce pseudo est déjà utilisé');
            } elseif ($password !== $checkPassword) {
                $this->session->addFlashes('error', 'Veuillez rentrer deux fois le même mot de passe');
            } else {
                try {
                    $this->userRepository->create($mail, $pseudo, $password);
                    $this->session->addFlashes('success', 'Compte créé avec succès');

                    $redirect = new \App\Service\RedirectResponse('?action=connexion');
                    $redirect->send();
                } catch (\PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $this->session->addFlashes('error', 'Ce pseudo ou cet email est déjà utilisé.');
                    } else {
                        $this->session->addFlashes('error', 'Une erreur est survenue. Veuillez réessayer.');
                    }
                }
            }
        }

        return $this->view->render(['office' => 'front', 'template' => 'inscription', 'data' => []]);
    }
}
