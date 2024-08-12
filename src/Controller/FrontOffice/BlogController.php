<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\View\View;
use App\Model\BlogModel;

final class BlogController
{
    public function __construct(private View $view, private BlogModel $blogModel)
    {
    }

    public function displayPage(): string
    {
        $posts = $this->blogModel->selectAllPosts();
            return $this->view->render(['template' => 'blog', 'data' => [
                'listPosts' => $posts
            ]]);
    }
}
