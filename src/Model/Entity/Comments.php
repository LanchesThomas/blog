<?php

declare(strict_types=1);

namespace App\Model\Entity;

final class Comments
{
    public function __construct(private readonly ?int $id, private string $statut, private string $createdAt, private string $content, private int $post, private int $userId, private ?string $pseudo)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getPost(): int
    {
        return $this->post;
    }

    public function setPost(string $pseudo): void
    {
        $this->post = $pseudo;
    }
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): void
    {
        $this->post = $pseudo;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->post = $userId;
    }
}
