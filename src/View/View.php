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
        $this->twig = new Environment($loader, ['debug' => true]);
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    }

    public function render(array $data): string
    {
        $data['data']['flashes'] = $this->session->getFlashes();
        $data['data']['user'] = $this->session->getUser();

        if ($data['template'] === 'admin') {
            return $this->twig->render("{$data['office']}office/{$data['template']}.html.twig", $data['data']);
        } else {
            return $this->twig->render("{$data['office']}office/{$data['template']}.html.twig", $data['data']);
        }
    }
}
