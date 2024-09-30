<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\BackOffice\AddPostController;
use App\Controller\BackOffice\AdminController;
use App\Controller\BackOffice\UserAdminController;
use App\Controller\BackOffice\ChangeRoleController;
use App\Controller\BackOffice\CommentAdminController;
use App\Controller\BackOffice\DeleteUser;
use App\Controller\FrontOffice\BlogController;
use App\Controller\FrontOffice\ConnexionController;
use App\Controller\FrontOffice\ErrorPageController;
use App\Controller\FrontOffice\HomeController;
use App\Controller\FrontOffice\InscriptionController;
use App\Controller\FrontOffice\PostController;
use App\Controller\FrontOffice\LogOutController;
use App\View\View;
use App\Service\Request;
use App\Service\ContactFormValidator;
use App\Service\Validator;
use App\Service\Session;
use App\Model\BlogModel;
use App\Model\Repository\PostsRepository;
use App\Model\Repository\CommentsRepository;
use App\Model\Repository\UserRepository;
use App\Service\MailerBlog;

final class Router
{
    private Session $session;
    private View $view;
    private PostsRepository $postsRepository;
    private CommentsRepository $commentsRepository;
    private UserRepository $userRepository;
    private MailerBlog $mailer;
    private Validator $validator;

    public function __construct(private Request $request)
    {
        $this->session = new Session();
        $this->view = new View($this->session);
        $this->postsRepository = new PostsRepository($this->session);
        $this->commentsRepository = new CommentsRepository();
        $this->userRepository = new UserRepository();
        $this->validator = new Validator();
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
                $postController = new PostController($this->view, $this->postsRepository, $post, $this->request, $this->commentsRepository, $this->session, $this->userRepository);
                return $postController->displayPage();
            }
            $blogController = new BlogController($this->view, $this->postsRepository);
            return $blogController->displayPage();
        } elseif ($action === 'connexion') {
            $connnexionController = new ConnexionController($this->view, $this ->request, $this->userRepository, $this->session);
            return $connnexionController->displayPage();
        } elseif ($action === 'inscription') {
            $loginController = new InscriptionController($this->view, $this->request, $this->userRepository, $this->session);
            return $loginController->displayPage();
        } elseif ($action === 'logout') {
            $logOutController = new LogOutController($this->session);
            return $logOutController->logOut();
        } elseif ($action === 'admin' && !$this->session->isAuthenticated()) {
            $connnexionController = new ConnexionController($this->view, $this ->request, $this->userRepository, $this->session);
            return $connnexionController->displayPage();
        } elseif ($this->session->isAuthenticated() && $this->session->getUser()['userRole'] === 'admin') {
            if ($action === 'admin') {
                $adminController = new AdminController($this->view, $this->postsRepository);
                return $adminController->displayPage();
            } elseif ($action === 'addPost') {
                $addPostController = new AddPostController($this->view, $this->postsRepository, $this->session, $this->request, $this->validator);
                return $addPostController->displayPage();
            } elseif ($action === 'userAdmin') {
                $userAdminController = new UserAdminController($this->view, $this->userRepository, $this->session, $this->request, $this->validator);
                return $userAdminController->displayPage();
            } elseif ($action === "changeRole") {
                $changeRoleController = new ChangeRoleController($this->userRepository, $this->request, $this->session);
                return $changeRoleController->ChangeRole();
            } elseif ($action === "deleteUser") {
                $deleteUser = new DeleteUser($this->userRepository, $this->request, $this->session);
                return $deleteUser->DeleteUser();
            } elseif ($action === 'commentAdmin' || $action === 'allComments' || $action === 'validComments' || $action === 'commentToValid' ||  $action === 'validateComment' || $action === 'invalidateComment' || $action === 'deleteComment') {
                $commentAdminController = new CommentAdminController($this->view, $this->commentsRepository, $this->session, $this->request, $this->validator);
                return $commentAdminController->displayPage();
            }
        }

        $errorController = new ErrorPageController($this->view);
            return $errorController->displayPage();
    }
}
