<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\View\View;
use App\Model\Repository\PostsRepository;

final class BlogController
{
    public function __construct(private View $view, private PostsRepository $postsRepository)
    {
    }

    public function displayPage(): string
    {
        $posts = $this->postsRepository->findAll();
            return $this->view->render(['template' => 'blog', 'data' => [
                'listPosts' => $posts
            ]]);
    }
}
