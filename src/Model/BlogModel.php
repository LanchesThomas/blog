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
        return $this->read('SELECT posts.id, posts.title, posts.content, posts.publicationDate, posts.chapo,
        posts.updateDate, users.pseudo FROM posts 
        INNER JOIN users ON posts.user_id = users.id
        WHERE id =' . $idPost);
    }

    public function selectIdPost()
    {
        return $this->readAll('SELECT idArticle FROM Article');
    }
}
