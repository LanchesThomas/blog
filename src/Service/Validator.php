<?php

declare(strict_types=1);

namespace App\Service;

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
}
