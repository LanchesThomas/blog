<?php

declare(strict_types=1);

namespace App\Service;

final class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['flashes'])) {
            $_SESSION['flashes'] = [];
        }

        if (!isset($_SESSION['old'])) {
            $_SESSION['old'] = [];
        }
    }

    public function addFlashes(string $type, string|array $message): void
    {
        $_SESSION['flashes'][] = [
            'type' => $type,
            'message' => $message,
        ];
        // echo '<pre>';
        //     var_dump($_SESSION['flashes']);
        // echo '</pre>';
        // die;
    }

    public function getFlashes(): array
    {
        $flashes = $_SESSION['flashes'];
        unset($_SESSION['flashes']);
        return $flashes;
    }

    public function setOldInput(array $input): void
    {
        $_SESSION['old'] = $input;
    }

    public function getOldInput(string $key, $default = ''): string
    {
        return $_SESSION['old'][$key] ?? $default;
    }

    public function clearOldInput(): void
    {
        unset($_SESSION['old']);
    }

    public function setUser(array $userData): void
    {
        $_SESSION['user'] = $userData;
        // echo '<pre>';
        //     var_dump($_SESSION['user']);
        // echo '</pre>';
        // die;
    }

    public function getUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public function clearUser(): void
    {
        unset($_SESSION['user']);
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }
}
