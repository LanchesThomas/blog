<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\View\View;
use App\Model\BlogModel;

final class PostController
{
    public function __construct(private View $view, private BlogModel $blogModel, private int $postId)
    {
        $this->postId = $postId;
    }

    public function displayPage(): string
    {
        $post = $this->blogModel->selectPost($this->postId);
        $comment = $this->blogModel->selectCommentByArticle($this->postId);
            return $this->view->render(['template' => 'post', 'data' => [
                'post' => $post, 'comments' => $comment
            ]]);
    }
}
