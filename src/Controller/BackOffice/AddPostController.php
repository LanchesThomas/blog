<?php

declare(strict_types=1);

namespace App\Controller\BackOffice;

use App\View\View;
use App\Model\Repository\PostsRepository;
use App\Service\Session;
use App\Service\Request;
use App\Service\Validator;

final class AddPostController
{
    public function __construct(private View $view, private PostsRepository $postsRepository, private Session $session, private Request $request, private validator $validator)
    {
    }

    public function displayPage(): string
    {
        if ($this->request->getMethod() === 'POST') {
            $title = $this->request->getRequestData('title');
            $chapo = $this->request->getRequestData('chapo');
            $content = $this->request->getRequestData('content');

            if ($this->validator->titleIsValid($title) && $this->validator->chapoIsValid($chapo) && $this->validator->contentIsValid($content)) {
                try {
                    $this->postsRepository->create($title, $chapo, $content);
                    $this->session->addFlashes('success', 'Article créé');
                } catch (\PDOException $e) {
                    $this->session->addFlashes('error', 'Impossible de créer l\'article');
                }
            }
        }


            return $this->view->render(['office' => 'back','template' => 'addPost', 'data' => []]);
    }
}
