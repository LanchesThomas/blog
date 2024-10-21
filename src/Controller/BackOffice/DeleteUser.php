<?php

declare(strict_types=1);

namespace App\Controller\BackOffice;

use App\Model\Repository\UserRepository;
use App\Service\Request;
use App\Service\Session;

/**
 * Deletes a user by their ID, except for the super admin (ID 1).
 *
 * @return void
 */


final class DeleteUser
{
    public function __construct(private UserRepository $userRepository, private Request $request, private Session $session)
    {
    }

    public function DeleteUser(): void
    {
        $id = (int)$this->request->queryAction('userId');

        try {
            if ($id !== 1) {
                $this->userRepository->delete($id);
                $this->session->addFlashes('success', 'Utilisateur supprimé avec succès');
                $redirect = new \App\Service\RedirectResponse('?action=userAdmin');
                $redirect->send();
            } else {
                $this->session->addFlashes('error', 'Impossible de supprimer cet utilisateur');
                $redirect = new \App\Service\RedirectResponse('?action=userAdmin');
                $redirect->send();
            }
        } catch (\Exception $e) {
            $this->session->addFlashes('error', 'Quelque chose s\'est mal passé');
            $redirect = new \App\Service\RedirectResponse('?action=userAdmin');
            $redirect->send();
        }
    }
}
