<?php

declare(strict_types=1);

namespace App\Model\Entity;

final class Comments
{
    public function __construct(private readonly int $id, private string $statut, private string $createdAt, private string $content, private string $post, private string $pseudo)
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

    public function getPost(): string
    {
        return $this->post;
    }

    public function setPost(string $post): void
    {
        $this->post = $post;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }
}
