<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Validates the contact form inputs (firstname, lastname, email, message).
 * Extends the base Validator class for specific validation rules.
 *
 * Methods:
 * - isValid(?string $firstname, ?string $lastname, ?string $email, ?string $message): Validates all fields and returns true if all are valid, otherwise false.
 * - getErrorMessage(): Returns an array of error messages generated during validation.
 *
 * Private Methods:
 * - isFirstnameValid($firstname): Validates the firstname field.
 * - isLastnameValid($lastname): Validates the lastname field.
 * - isEmailValid($email): Validates the email field.
 * - isMessageValid($message): Validates the message field.
 */


final class ContactFormValidator extends Validator
{
    private array $errorMessage = [];

    public function __construct()
    {
    }

    public function isValid(?string $firstname, ?string $lastname, ?string $email, ?string $message): bool
    {
        $isValidFirstname = $this->isFirstnameValid($firstname);
        $isValidLastName = $this->isLastnameValid($lastname);
        $isValidEmail = $this->isEmailValid($email);
        $isValidMessage = $this->isMessageValid($message);
        return $isValidFirstname && $isValidLastName && $isValidEmail && $isValidMessage;
    }

    private function isFirstnameValid($firstname): bool
    {
        $isEmpty = strlen(trim($firstname)) === 0 ;
        if ($isEmpty) {
            $this->errorMessage[] = 'Le champ prénom ne peut pas être vide';
            return false;
        }
        $isValid = $firstname !== null && $this->nameIsValid($firstname);
        if (!$isValid) {
            $this->errorMessage[] = 'Le champ prénom ne peut pas contenir de chiffres ou de caractères spéciaux';
            return false;
        }
        return true;
    }

    private function isLastnameValid($lastname): bool
    {
        $isEmpty = strlen(trim($lastname)) === 0 ;
        if ($isEmpty) {
            $this->errorMessage[] = 'Le champ nom ne peut pas être vide';
            return false;
        }
        $isValid = $lastname !== null && $this->nameIsValid($lastname);
        if (!$isValid) {
            $this->errorMessage[] = 'Le champ nom ne peut pas contenir de chiffres ou de caractères spéciaux';
            return false;
        }
        return true;
    }

    private function isEmailValid($email): bool
    {
        $isEmpty = strlen(trim($email)) === 0 ;
        if ($isEmpty) {
            $this->errorMessage[] = 'Le champ email ne peut pas être vide';
            return false;
        }
        $isValid = $email !== null && $this->emailIsValid($email);
        if (!$isValid) {
            $this->errorMessage[] = 'Le champ email ne peut pas contenir de chiffres ou de caractères spéciaux';
            return false;
        }
        return true;
    }

    private function isMessageValid($message): bool
    {
        $isEmpty = strlen(trim($message)) === 0;
        if ($isEmpty) {
            $this->errorMessage[] = 'Le champ message ne peut pas être vide';
            return false;
        }

        $isTooShort = strlen(trim($message)) <= 40;
        if ($isTooShort) {
            $this->errorMessage[] = 'Le message doit contenir plus de 40 caractères';
            return false;
        }

        return true;
    }

    public function getErrorMessage(): array
    {
        return $this->errorMessage;
    }
}
