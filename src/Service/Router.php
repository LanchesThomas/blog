<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\BackOffice\AddPostController;
use App\Controller\BackOffice\AdminController;
use App\Controller\BackOffice\UserAdminController;
use App\Controller\BackOffice\ChangeRoleController;
use App\Controller\BackOffice\CommentAdminController;
use App\Controller\BackOffice\DeleteUser;
use App\Controller\BackOffice\UpdatePostController;
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
use App\Model\Repository\PostsRepository;
use App\Model\Repository\CommentsRepository;
use App\Model\Repository\UserRepository;
use App\Service\MailerBlog;

/**
 * Main application router responsible for handling different actions and routing requests to appropriate controllers.
 *
 * Constructor:
 * - __construct(Request $request): Initializes the router with the request and sets up dependencies like session, view, repositories, and services.
 *
 * Methods:
 * - run(): Determines the action based on the request query and routes the request to the corresponding controller. It handles various actions like 'home', 'blog', 'connexion', 'inscription', 'admin', and more, depending on user authentication and roles. Returns the appropriate view or page content.
 */


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
            $connnexionController = new ConnexionController($this->view, $this->request, $this->userRepository, $this->session);
            return $connnexionController->displayPage();
        } elseif ($this->session->isAuthenticated() && $this->session->getUser()['userRole'] === 'admin') {
            if ($action === 'admin' || $action === "publishedPost" || $action === "draftedPost" || $action === "deletedPost" || $action === "seeMore" || $action === "seeLess") {
                $adminController = new AdminController($this->view, $this->postsRepository, $this->request, $this->session);
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
            } elseif ($action === 'commentAdmin' || $action === 'allComments' || $action === 'validComments' || $action === 'commentToValid' ||  $action === 'validateComment' || $action === 'invalidateComment' || $action === 'deleteComment' || $action === 'CseeMore' || $action === 'CseeLess') {
                $commentAdminController = new CommentAdminController($this->view, $this->commentsRepository, $this->session, $this->request, $this->validator, $this->postsRepository);
                return $commentAdminController->displayPage();
            } elseif ($action === "updatePost") {
                $updatePostController = new UpdatePostController($this->postsRepository, $this->request, $this->session, $this->view, $this->userRepository);
                return $updatePostController->displayPage();
            }
        }

        $errorController = new ErrorPageController($this->view);
            return $errorController->displayPage();
    }
}
