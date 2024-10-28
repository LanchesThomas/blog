<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\View\View;
use App\Model\Repository\PostsRepository;
use App\Service\Request;
use App\Model\Repository\CommentsRepository;
use App\Model\Repository\UserRepository;
use App\Service\Session;

/**
 * Displays a single blog post page along with its comments.
 * Handles comment submission (POST request), adding the comment to the repository if valid, and provides feedback to the session.
 * Fetches and displays a limited number of comments, with the option to load more.
 * Renders the 'post' template with the post and comment data.
 *
 * @return string The rendered view of the Post page.
 */


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
            $totalComments = $comments ? count($this->commentsRepository->findBy(['post_id' => $this->postId, 'statut' => 'valid'])) : 0;

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
