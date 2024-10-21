<?php

declare(strict_types=1);

namespace App\Controller\FrontOffice;

use App\View\View;
use App\Model\Repository\PostsRepository;

/**
 * Displays the blog page by fetching the latest published posts, limited to 6.
 * Renders the 'blog' template with the list of posts.
 *
 * @return string The rendered view of the Blog page.
 */


final class BlogController
{
    public function __construct(private View $view, private PostsRepository $postsRepository)
    {
    }

    public function displayPage(): string
    {
        $posts = $this->postsRepository->findBy(['statut' => 'published'], ['createdAt' => 'DESC'], 6);
            return $this->view->render(['office' => 'front','template' => 'blog', 'data' => [
                'listPosts' => $posts
            ]]);
    }
}
