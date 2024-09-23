<?php

declare(strict_types=1);

namespace App\Model\Entity;

final class User
{
    public function __construct(private readonly ?int $id, private string $pseudo, private string $mail, private string $password, private string $role)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        // Optional: Validate that the role is one of the allowed values
        if (!in_array($role, ['user', 'editor', 'admin'])) {
            throw new \InvalidArgumentException("Invalid role: $role");
        }
        $this->role = $role;
    }
}
