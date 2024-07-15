<?php

declare(strict_types=1);

namespace App\View;

use App\Service\Session;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

final class View
{
    private Environment $twig;

    public function __construct(private Session $session)
    {
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $this->twig = new Environment($loader);
    }

    public function render(array $data): string
    {
        $data['data']['flashes'] = $this->session->getFlashes();

        // var_dump($data);
        // die();
        return $this->twig->render("frontoffice/{$data['template']}.html.twig", $data['data']);
    }
}
