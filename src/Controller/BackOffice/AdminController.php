<?php

declare(strict_types=1);

namespace App\Controller\BackOffice;

use App\View\View;
use App\Model\Repository\PostsRepository;

final class AdminController
{
    public function __construct(private View $view, private PostsRepository $postsRepository)
    {
    }

    public function displayPage(): string
    {
        $posts = $this->postsRepository->findAll();

            return $this->view->render(['office' => 'back','template' => 'admin', 'data' => [
                'listPosts' => $posts
            ]]);
    }
}
