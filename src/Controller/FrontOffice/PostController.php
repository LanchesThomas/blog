<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\View\View;
use App\Model\BlogModel;
use App\Model\Repository\PostsRepository;

final class PostController
{
    public function __construct(private View $view, private PostsRepository $postsRepository, private int $postId)
    {
        $this->postId = $postId;
    }

    public function displayPage(): string
    {
        $post = $this->postsRepository->find($this->postId);
        // $comment = $this->postsRepository->selectCommentByArticle($this->postId);
            return $this->view->render(['template' => 'post', 'data' => [
                'post' => $post
            ]]);
    }
}
