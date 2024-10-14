<?php

declare(strict_types=1);

namespace App\Controller\BackOffice;

use App\Model\Entity\Comments;
use App\Model\Repository\CommentsRepository;
use App\View\View;
use App\Model\Repository\PostsRepository;
use App\Model\Repository\UserRepository;
use App\Service\Session;
use App\Service\Request;
use App\Service\Validator;

final class CommentAdminController
{
    public function __construct(private View $view, private CommentsRepository $commentsRepository, private Session $session, private Request $request, private validator $validator, private PostsRepository $postsRepository)
    {
    }

    public function displayPage(): string
    {
        $list = $this->request->queryAction('list');
        $commentId = (int)$this->request->queryAction('commentId');
        $action = $this->request->queryAction('action');

        $limit  = 6;
        if ($action === "CseeMore") {
            $limit += 3;
        }
        if ($action === "CseeLess") {
            $limit = 6;
        }

        $comments = $this->commentsRepository->findBy([], ['createdAt' => 'DESC'], $limit);
        $totalComments = $comments ? count($this->commentsRepository->findBy([], ['createdAt' => 'DESC'])) : 0;

        $postTitles = [];
        if ($comments) {
            foreach ($comments as $comment) {
                $postId = $comment->getPost();
                $postTitle = $this->postsRepository->findOneBy(['id' => $postId])->getTitle();
                array_push($postTitles, $postTitle);
            }
        }



        if ($list === "validComments") {
            $comments = $this->commentsRepository->findBy(['statut' => 'valid'], ['createdAt' => 'DESC'], $limit);
            if ($comments) {
                foreach ($comments as $comment) {
                    $postId = $comment->getPost();
                    $postTitle = $this->postsRepository->findOneBy(['id' => $postId])->getTitle();
                    array_push($postTitles, $postTitle);
                }
            }
            $totalComments = $comments ? count($this->commentsRepository->findBy(['statut' => 'valid'], ['createdAt' => 'DESC'])) : 0;
        }

        if ($list === "commentToValid") {
            $comments = $this->commentsRepository->findBy(['statut' => 'waiting'], ['createdAt' => 'DESC'], $limit);
            if ($comments) {
                foreach ($comments as $comment) {
                    $postId    = $comment->getPost();
                    $postTitle = $this->postsRepository->findOneBy(['id' => $postId])->getTitle();
                    array_push($postTitles, $postTitle);
                }
            }
            $totalComments = $comments ? count($this->commentsRepository->findBy(['statut' => 'waiting'], ['createdAt' => 'DESC'])) : 0;
        }

        if ($list === "invalidComments") {
            $comments = $this->commentsRepository->findBy(['statut' => 'delete'], ['createdAt' => 'DESC'], $limit);
            if ($comments) {
                foreach ($comments as $comment) {
                    $postId = $comment->getPost();
                    $postTitle = $this->postsRepository->findOneBy(['id' => $postId])->getTitle();
                    array_push($postTitles, $postTitle);
                }
            }
            $totalComments = $comments ? count($this->commentsRepository->findBy(['statut' => 'delete'], ['createdAt' => 'DESC'])) : 0;
        }

        if ($action === "validateComment") {
            try {
                $comments = $this->commentsRepository->update($commentId, 'valid');
                $this->session->addFlashes('success', 'Votre commentaire a été validé avec succès.');
                $redirect = new \App\Service\RedirectResponse('?action=commentAdmin&list=' . $list);
                $redirect->send();
            } catch (\Exception $e) {
                $this->session->addFlashes('success', 'Echec de la validation du commentaire');
            }
        }

        if ($action === "invalidateComment") {
            try {
                $comments = $this->commentsRepository->update($commentId, 'delete');
                $this->session->addFlashes('success', 'Votre commentaire a été invalidé avec succès.');
                $redirect = new \App\Service\RedirectResponse('?action=commentAdmin&list=' . $list);
                $redirect->send();
            } catch (\Exception $e) {
                $this->session->addFlashes('success', 'Echec de la suppression du commentaire');
            }
        }

        if ($action === "deleteComment") {
            try {
                $comments = $this->commentsRepository->delete($commentId);
                $this->session->addFlashes('success', 'Votre commentaire a été supprimé définitivement.');
                $redirect = new \App\Service\RedirectResponse('?action=commentAdmin&list=' . $list);
                $redirect->send();
            } catch (\Exception $e) {
                $this->session->addFlashes('success', 'Echec de la suppression définitive du commentaire');
            }
        }

            return $this->view->render(['office' => 'back','template' => 'commentAdmin', 'data' => [
                'comments' => $comments, 'list' => $list, 'totalComments' => $totalComments, 'limit' => $limit, 'postTitles' => $postTitles
            ]]);
    }
}
