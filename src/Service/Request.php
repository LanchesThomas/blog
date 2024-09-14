<?php

declare(strict_types=1);

namespace App\Service;

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
