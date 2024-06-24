<?php

declare(strict_types=1);

namespace App\Service;

final class Request
{
    public function __construct(private array $query, private array $request, private array $files, private array $server)
    {
        // Sécurité : nettoyer les super globales
    }

    public function query(): array
    {
        return $this->query;
    }

    public function request(): array
    {
        return $this->request;
    }

    public function files(): array
    {
        return $this->files;
    }

    public function server(): array
    {
        return $this->server;v
    }

    public function queryAction(string $key): string
    {
        return '';
    }
}
