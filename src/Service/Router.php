<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\FrontOffice\BlogController;
use App\Controller\FrontOffice\ConnexionController;
use App\Controller\FrontOffice\ErrorPageController;
use App\Controller\FrontOffice\HomeController;
use App\Controller\FrontOffice\InscriptionController;
use App\Controller\FrontOffice\PostController;
use App\View\View;
use App\Service\Request;
use App\Service\ContactFormValidator;
use App\Service\Session;
use App\Model\BlogModel;
use App\Model\Repository\PostsRepository;
use App\Model\Repository\CommentsRepository;
use App\Service\MailerBlog;

final class Router
{
    private Session $session;
    private View $view;
    private PostsRepository $postsRepository;
    private CommentsRepository $commentsRepository;
    private MailerBlog $mailer;

    public function __construct(private Request $request)
    {
        $this->session = new Session();
        $this->view = new View($this->session);
        $this->postsRepository = new PostsRepository();
        $this->commentsRepository = new CommentsRepository();
        $this->mailer  = new MailerBlog(['smtp' => '127.0.0.1', 'smtp_port' => '1025', 'from' => 'infoblog@mail.fr', 'sender' => 'infoblog']);
    }

    public function run(): string
    {
        $query  = $this->request->query();
        $action = $query['action'] ?? 'home';
        $post = isset($query['postId']) ? (int) $query['postId'] : null;
        if ($action === 'home') {
            $contactValidator = new ContactFormValidator();
            $homeController = new HomeController($this->request, $contactValidator, $this->view, $this->session, $this->mailer);
            return $homeController->displayPage();
        } elseif ($action === 'blog') {
            if (isset($post) && $post) {
                $postController = new PostController($this->view, $this->postsRepository, $post, $this->request, $this->commentsRepository);
                return $postController->displayPage();
            }
            $blogController = new BlogController($this->view, $this->postsRepository);
            return $blogController->displayPage();
        } elseif ($action === 'connexion') {
            $connnexionController = new ConnexionController($this->view);
            return $connnexionController->displayPage();
        } elseif ($action === 'inscription') {
            $loginController = new InscriptionController($this->view);
            return $loginController->displayPage();
        }

        $errorController = new ErrorPageController($this->view);
            return $errorController->displayPage();
    }
}
