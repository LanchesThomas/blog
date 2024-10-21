<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Provides validation methods for various input types such as email, password, name, and message.
 *
 * Methods:
 * - emailIsValid(string $email): Validates an email address by sanitizing and checking its format.
 * - passwordIsValid(string $password): Validates a password ensuring it has at least 8 characters, including uppercase, lowercase, digits, and special characters.
 * - nameIsValid(string $name): Validates that a name contains only letters and spaces.
 * - messageIsValid(string $message): Validates that a message is not empty and does not contain HTML tags.
 * - titleIsValid(string $title): Validates that a title is not empty and does not contain HTML tags.
 * - chapoIsValid(string $chapo): Validates that a chapo (introductory text) is not empty and does not contain HTML tags.
 * - contentIsValid(string $content): Validates that the content is not empty and does not contain HTML tags.
 */

class Validator
{
    public function __construct()
    {
    }

    public function emailIsValid(string $email): bool
    {
        $sanitizeEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        return filter_var($sanitizeEmail, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function passwordIsValid(string $password): bool
    {
        // Au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        return preg_match($pattern, $password) === 1;
    }

    public function nameIsValid(string $name): bool
    {
        //Lettres et espaces uniquement
        return preg_match('/^[a-zA-Z\s]+$/', $name) === 1;
    }

    public function messageIsValid(string $message): bool
    {
        // Vérifie que le message n'est pas vide et qu'il ne contient pas de balises HTML
        $strippedMessage = strip_tags($message);
        return !empty($strippedMessage) && $strippedMessage === $message;
    }

    public function titleIsValid(string $title): bool
    {
        // Vérifie que le message n'est pas vide et qu'il ne contient pas de balises HTML
        $strippedMessage = strip_tags($title);
        return !empty($strippedMessage) && $strippedMessage === $title;
    }

    public function chapoIsValid(string $chapo): bool
    {
        // Vérifie que le message n'est pas vide et qu'il ne contient pas de balises HTML
        $strippedMessage = strip_tags($chapo);
        return !empty($strippedMessage) && $strippedMessage === $chapo;
    }

    public function contentIsValid(string $content): bool
    {
        // Vérifie que le message n'est pas vide et qu'il ne contient pas de balises HTML
        $strippedMessage = strip_tags($content);
        return !empty($strippedMessage) && $strippedMessage === $content;
    }
}
