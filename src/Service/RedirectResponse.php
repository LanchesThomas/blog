<?php

declare(strict_types=1);

namespace App\Service;

class RedirectResponse
{
    public function __construct(private string $url, private int $statusCode = 302)
    {
        $this->url = $url;
        $this->statusCode = $statusCode;
    }

    public function send(): void
    {
        if (!headers_sent()) {
            http_response_code($this->statusCode);
            header('Location: ' . $this->url);
            exit();
        } else {
            echo "<script>window.location.href='" . htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8') . "';</script>";
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8') . '">';
            echo '</noscript>';
            exit();
        }
    }
}
