<?php

declare(strict_types=1);

namespace App\Model\Entity;

/**
 * Represents a blog post.
 *
 * @property ?int $id The unique identifier of the post (nullable).
 * @property string $title The title of the post.
 * @property string $content The main content of the post.
 * @property string $createdAt The date and time the post was created.
 * @property string $chapo The introductory or summary text of the post.
 * @property string $updatedAt The date and time the post was last updated.
 * @property string $pseudo The pseudonym of the author of the post.
 * @property int $userId The ID of the user who created the post.
 * @property string $statut The status of the post (e.g., 'published', 'draft').
 */


final class Post
{
    public function __construct(
        private readonly ?int $id,
        private string $title,
        private string $content,
        private string $createdAt,
        private string $chapo,
        private string $updatedAt,
        private string $pseudo,
        private int $userId,
        private string $statut
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getChapo(): string
    {
        return $this->chapo;
    }

    public function setChapo(string $chapo): void
    {
        $this->chapo = $chapo;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }
}
