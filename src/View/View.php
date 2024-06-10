<?php

declare(strict_types=1);
namespace App\View;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

final class View
{
    private $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $this->twig = new Environment($loader);
    }

    public function render(array $data): string
    {
        return $this->twig->render("frontoffice/{$data['template']}.html.twig", $data['data']);
    }
}
