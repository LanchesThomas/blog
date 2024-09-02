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

final class PostController
{
    public function __construct(private View $view, private PostsRepository $postsRepository, private int $postId, private Request $request, private CommentsRepository $commentsRepository)
    {
        $this->postId = $postId;
    }

    public function displayPage(): string
    {
        $post = $this->postsRepository->find($this->postId);
        if ($this->request->getMethod() === 'POST') {
            $comment = $this->request->getRequestData('comments') ?? '';
            $this->commentsRepository->create($post, $comment);
        }
         $comments = $this->commentsRepository->findBy(['post_id' => $this->postId], ['createdAt' => 'ASC'], 3);
            return $this->view->render(['template' => 'post', 'data' => [
                'post' => $post, 'comments' => $comments
            ]]);
    }
}
