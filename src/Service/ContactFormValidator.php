<?php

declare(strict_types=1);

namespace App\Service;

class ContactFormValidator extends Validator
{
    public function __construct()
    {
    }

    public function isValid(?string $firstname, ?string $lastname, ?string $email, ?string $message): bool
    {
        return $this->isFirstnameValid($firstname) && $this->isLastnameValid($lastname) && $this->isEmailValid($email) && $this->isMessageValid($message);
    }

    private function isFirstnameValid($firstname): bool
    {
        return $firstname !== null && $this->nameIsValid($firstname);
    }

    private function isLastnameValid($lastname): bool
    {
        return $lastname !== null && $this->nameIsValid($lastname);
    }

    private function isEmailValid($email): bool
    {
        return $email !== null && $this->emailIsValid($email);
    }

    private function isMessageValid($message): bool
    {
        return $message !== null && $this->messageIsValid($message);
    }
}
