<?php

declare(strict_types=1);

namespace App\Service;

class ContactFormValidator extends Validator
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
        return $message !== null && $this->messageIsValid($message);
    }

    public function getErrorMessage(): array
    {
        return $this->errorMessage;
    }
}
