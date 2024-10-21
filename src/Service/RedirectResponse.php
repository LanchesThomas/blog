<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Handles HTTP redirection responses.
 *
 * Constructor:
 * - __construct(string $url, int $statusCode = 302): Initializes the redirect with a URL and optional HTTP status code (default is 302 for a temporary redirect).
 *
 * Methods:
 * - send(): Sends the redirect header if headers have not already been sent, or uses JavaScript and a meta refresh tag as a fallback. Terminates the script after execution.
 */


final class RedirectResponse
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
