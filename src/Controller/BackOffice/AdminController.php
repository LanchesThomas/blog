<?php

declare(strict_types=1);

namespace App\Controller\BackOffice;

use App\View\View;
use App\Model\Repository\PostsRepository;
use App\Service\Request;
use App\Service\Session;

final class AdminController
{
    public function __construct(private View $view, private PostsRepository $postsRepository, private Request $request, private Session $session)
    {
    }

    public function displayPage(): string
    {
        $posts = $this->postsRepository->findBy([], ['createdAt' => 'DESC'], 6);
        $list = $this->request->queryAction('list');
        $action = $this->request->queryAction('action');
        $postId = (int)$this->request->queryAction('postId');
        $totalsPosts   = 0;

        var_dump($this->session->getUser());
        die();

        $limit  = 6;
        if ($action === "seeMore") {
            $limit += 3;
        }
        if ($action === "seeLess") {
            $limit = 6;
        }

        if ($list === 'all') {
            $posts = $this->postsRepository->findBy([], ['createdAt' => 'DESC'], $limit);
            $totalsPosts   = $posts ? count($this->postsRepository->findBy([], ['createdAt' => 'DESC'])) : 0;
        }
        if ($list === 'published') {
            $posts = $this->postsRepository->findBy(['statut' => 'published'], ['createdAt' => 'DESC'], $limit);
            $totalsPosts = $posts ? count($this->postsRepository->findBy(['statut' => 'published'], ['createdAt' => 'DESC'])) : 0;
        }
        if ($list === 'draft') {
            $posts = $this->postsRepository->findBy(['statut' => 'draft'], ['createdAt' => 'DESC'], $limit);
            $totalsPosts = $posts ? count($this->postsRepository->findBy(['statut' => 'draft'], ['createdAt' => 'DESC'])) : 0;
        }

        if ($action === 'publishedPost') {
            try {
                $posts = $this->postsRepository->update($postId, 'published', null, null, null);
                $this->session->addFlashes('success', 'L\'article a été publié avec succès.');
                $redirect = new \App\Service\RedirectResponse('?action=admin&list=' . $list);
                $redirect->send();
            } catch (\Exception $e) {
                $this->session->addFlashes('error', 'Quelque chose s\'est mal passé.');
            }
        }
        if ($action === 'draftedPost') {
            try {
                $posts = $this->postsRepository->update($postId, 'draft', null, null, null);
                $this->session->addFlashes('success', 'L\'article a été mis en brouillon avec succès.');
                $redirect = new \App\Service\RedirectResponse('?action=admin&list=' . $list);
                $redirect->send();
            } catch (\Exception $e) {
                $this->session->addFlashes('error', 'Quelque chose s\'est mal passé.');
            }
        }

        if ($action === "deletedPost") {
            try {
                $this->postsRepository->delete($postId);
                $this->session->addFlashes('success', 'L\'article a été supprimé avec succès.');
                $redirect = new \App\Service\RedirectResponse('?action=admin&list=' . $list);
                $redirect->send();
            } catch (\Exception $e) {
                $this->session->addFlashes('error', 'Quelque chose s\'est mal passé.');
            }
        }

            return $this->view->render(['office' => 'back','template' => 'admin', 'data' => [
                'listPosts' => $posts, 'list' => $list, 'limit' => $limit, 'totalPosts' => $totalsPosts
                ]]);
    }
}
