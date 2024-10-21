<?php

declare(strict_types=1);

namespace App\Model\Entity;

/**
 * Represents a comment on a blog post.
 *
 * @property ?int $id The unique identifier of the comment (nullable).
 * @property string $statut The status of the comment (e.g., 'valid', 'waiting', 'deleted').
 * @property string $createdAt The date and time the comment was created.
 * @property string $content The text content of the comment.
 * @property int $post The ID of the post to which the comment belongs.
 * @property int $userId The ID of the user who created the comment.
 * @property ?string $pseudo The pseudonym of the user who created the comment.
 */


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
