<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Handles session management, including user data, flash messages, and old input data.
 *
 * Constructor:
 * - __construct(): Starts a session if none exists and initializes session variables for flash messages and old input data.
 *
 * Methods:
 * - addFlashes(string $type, string|array $message): Adds a flash message of a given type (e.g., 'success', 'error').
 * - getFlashes(): Retrieves and clears all flash messages from the session.
 * - setOldInput(array $input): Stores form input data for future access.
 * - getOldInput(string $key, $default = ''): Retrieves a specific old input value or a default value if not found.
 * - clearOldInput(): Clears old input data from the session.
 * - setUser(array $userData): Stores user data in the session.
 * - getUser(): Retrieves the currently logged-in user data or null if not authenticated.
 * - clearUser(): Clears user data from the session.
 * - isAuthenticated(): Checks if a user is logged in by verifying if user data exists in the session.
 */


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
