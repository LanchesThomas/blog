<?php

declare(strict_types=1);

namespace App\Model;

class BlogModel extends MainModel
{
    public function selectAllPosts()
    {
        return $this->readAll('SELECT * FROM `posts`');
    }

    public function selectPost($idPost)
    {
        return $this->read('SELECT posts.id, posts.title, posts.content, posts.createdAt, posts.chapo,
        posts.updatedAt, users.pseudo FROM posts 
        INNER JOIN users ON posts.user_id = users.id
        WHERE posts.id =' . $idPost);
    }

    public function selectIdPost()
    {
        return $this->readAll('SELECT idArticle FROM Article');
    }

    public function selectCommentByArticle($idPost)
    {
        return $this->readAll('SELECT comments.content, comments.user_id, comments.createdAt, users.pseudo FROM comments
        INNER JOIN posts ON posts.id = comments.post_id
        INNER JOIN users ON comments.user_id = users.id
        WHERE comments.statut = 1 AND posts.id = ' . $idPost);
    }
}
