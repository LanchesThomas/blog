<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\View\View;
use App\Model\BlogModel;
use App\Model\Repository\PostsRepository;
use App\Service\Request;
use App\Controller\FrontOffice\CommentsController;
use App\Model\Entity\Comments;
use App\Model\Repository\CommentsRepository;
use App\Model\Repository\UserRepository;
use App\Service\Session;

final class PostController
{
    public function __construct(private View $view, private PostsRepository $postsRepository, private int $postId, private Request $request, private CommentsRepository $commentsRepository, private Session $session, private UserRepository $userRepository)
    {
        $this->postId = $postId;
    }

    public function displayPage(): string
    {
        $postId = $this->postsRepository->find($this->postId);
        if ($this->request->getMethod() === 'POST') {
            $comment = $this->request->getRequestData('comments') ?? '';
            $userId = $this->session->getUser()['userId'];
            $user = $this->userRepository->findOneBy(['id' => $userId]);
            $pseudo = $user->getPseudo();
            try {
                $this->commentsRepository->create($postId, $comment, $userId, $pseudo);
                $this->session->addFlashes('success', 'Votre commentaire a été envoyé avec succès.');
            } catch (\Exception $e) {
                $this->session->addFlashes('error', 'Échec de l\'envoi du commentaire. Veuillez réessayer plus tard.' . $e);
            }
        }

        $limit  = 3;

        if ($this->request->queryAction('c')) {
            $limit = $this->request->queryAction('c') + 3;
        }



        $comments = $this->commentsRepository->findBy(['post_id' => $this->postId, 'statut' => 'valid'], ['createdAt' => 'DESC'], $limit);

        $is_comments   = $this->commentsRepository->findBy(['post_id' => $this->postId]) != null;
        if ($is_comments) {
            $totalComments = count($this->commentsRepository->findBy(['post_id' => $this->postId]));

                return $this->view->render(['office' => 'front', 'template' => 'post', 'data' => [
                    'post' => $postId, 'comments' => $comments, 'totalComments' => $totalComments, 'limit' => $limit
                ]]);
        } else {
            return $this->view->render(['office' => 'front', 'template' => 'post', 'data' => [
                'post' => $postId
            ]]);
        }
    }
}
