<?php

declare(strict_types=1);

namespace App\Controller\BackOffice;

use App\Model\Repository\UserRepository;
use App\Service\Request;
use App\Service\Session;

final class ChangeRoleController
{
    public function __construct(private UserRepository $userRepository, private Request $request, private Session $session)
    {
    }

    public function ChangeRole(): void
    {
        $id = (int)$this->request->queryAction('userId');
        $role = $this->request->queryAction('role');

        try {
            if ($id !== 1) {
                if ($role === 'editor') {
                    $this->userRepository->update($id, 'admin');
                    $this->session->addFlashes('success', 'Utilisateur modifié avec succès');
                    $redirect = new \App\Service\RedirectResponse('?action=userAdmin');
                    $redirect->send();
                } elseif ($role === 'admin') {
                    $this->userRepository->update($id, 'editor');
                    $this->session->addFlashes('success', 'Utilisateur modifié avec succès');
                    $redirect = new \App\Service\RedirectResponse('?action=userAdmin');
                    $redirect->send();
                }
            } else {
                $this->session->addFlashes('error', 'Impossible de modifier cet utilisateur');
                $redirect = new \App\Service\RedirectResponse('?action=userAdmin');
                $redirect->send();
            }
        } catch (\Exception $e) {
                $this->session->addFlashes('error', 'Quelque chose s\est mal passé');
                $redirect = new \App\Service\RedirectResponse('?action=userAdmin');
                $redirect->send();
        }
    }
}
