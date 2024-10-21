<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Handles HTTP request data by encapsulating and sanitizing the global arrays ($_GET, $_POST, $_FILES, $_SERVER).
 *
 * Constructor:
 * - __construct(array $query, array $request, array $files, array $server): Initializes the request object with the respective global arrays.
 *
 * Methods:
 * - query(): Returns the $_GET array.
 * - request(): Returns the $_POST array.
 * - files(): Returns the $_FILES array.
 * - server(): Returns the $_SERVER array.
 * - queryAction(string $key): Retrieves a value from the $_GET array by key, or null if not set.
 * - getRequestData(string $key): Retrieves and sanitizes a value from the $_POST array by key, or null if not set.
 * - getMethod(): Returns the HTTP request method from the $_SERVER array.
 *
 * Private Methods:
 * - sanitizeInput(string $input): Removes HTML tags from input to prevent XSS attacks.
 */


final class Request
{
    private function sanitizeInput(string $input): ?string
    {
        return is_string($input) ? strip_tags($input) : null;
    }


    public function __construct(private array $query, private array $request, private array $files, private array $server)
    {
        // Sécurité : nettoyer les super globales
    }

    // $_GET
    public function query(): array
    {
        return $this->query;
    }

    // $_POST
    public function request(): array
    {
        return $this->request;
    }

    // $_FILES
    public function files(): array
    {
        return $this->files;
    }

    //$_SERVER
    public function server(): array
    {
        return $this->server;
    }


    public function queryAction(string $key): ?string
    {
        return $this->query[$key] ?? null;
    }

    public function getRequestData(string $key): ?string
    {
        return $this->sanitizeInput($this->request[$key]) ?? null;
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }
}
