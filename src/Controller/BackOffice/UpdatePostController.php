<?php

declare(strict_types=1);

namespace App\Controller\BackOffice;

use App\Model\Repository\PostsRepository;
use App\Model\Repository\UserRepository;
use App\Service\Request;
use App\Service\Session;
use App\View\View;

/**
 * Displays and processes the post update form.
 * If the form is submitted (POST request), it updates the post's title, chapo, content, and author in the repository.
 * Fetches the post and list of admin users to render the 'updatePost' template.
 *
 * @return string The rendered view of the Update Post page.
 */


final class UpdatePostController
{
    public function __construct(private PostsRepository $postsRepository, private Request $request, private Session $session, private View $view, private UserRepository $userRepository)
    {
    }

    public function displayPage(): string
    {
        $id = (int)$this->request->queryAction('postId');

        if ($this->request->getMethod() === "POST") {
            $updateTitle = $this->request->getRequestData('title');
            $updateChapo = $this->request->getRequestData('chapo');
            $updateContent = $this->request->getRequestData('content');
            $updateAuthor = $this->request->getRequestData('author');
            $updateUserId  = $this->userRepository->findOneBy(['pseudo' => $updateAuthor])->getId();
            $statut = 'published';

            try {
                $this->postsRepository->update($id, $statut, $updateTitle, $updateChapo, $updateContent, $updateUserId);
                $this->session->addFlashes('success', 'L\'article à été modifié avec succès.');
            } catch (\Exception $e) {
                $this->session->addFlashes('error', 'Quelque chose s\'est mal passé.');
            }
        }

        try {
            $post = $this->postsRepository->findOneBy(['id' => $id]);
            $users = $this->userRepository->findBy(['role' => 'admin']);

            return $this->view->render(['office' => 'back','template' => 'updatePost', 'data' => [
                'post' => $post, 'users' => $users
                ]]);
        } catch (\Exception $e) {
            $this->session->addFlashes('error', 'Quelque chose s\'est mal passé.');
        }
    }
}
