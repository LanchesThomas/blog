<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\User;
use App\Model\Entity\Post;
use App\Service\ConnectDB;

final readonly class PostsRepository
{
    public function __construct()
    {
    }

    public function findAll(): array
    {
        $req = ConnectDB::getPDO()->prepare('
            SELECT posts.id, posts.title, posts.content, posts.createdAt, posts.chapo,
                   posts.updatedAt, users.pseudo 
            FROM posts
            INNER JOIN users ON posts.user_id = users.id
        ');
        $req->execute();
        $results = $req->fetchAll(\PDO::FETCH_ASSOC);

        $posts = [];
        foreach ($results as $data) {
            $posts[] = new Post(
                id: (int)$data['id'],
                title: $data['title'],
                content: $data['content'],
                createdAt: $data['createdAt'],
                chapo: $data['chapo'],
                updatedAt: $data['updatedAt'],
                pseudo: $data['pseudo']
            );
        }

        return $posts;
    }




    public function find(int $id): ?Post
    {
            $req = ConnectDB::getPDO()->prepare(
                'SELECT posts.id, posts.title, posts.content, posts.createdAt, posts.chapo,
                posts.updatedAt, users.pseudo 
                FROM posts 
                INNER JOIN users ON posts.user_id = users.id 
                WHERE posts.id = :id'
            );
            $req->bindValue(':id', $id, \PDO::PARAM_INT);
            $req->execute();
            $data = $req->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

            return new Post(
                id: (int)$data['id'],
                title: $data['title'],
                content: $data['content'],
                createdAt: $data['createdAt'],
                chapo: $data['chapo'],
                updatedAt: $data['updatedAt'],
                pseudo: $data['pseudo']
            );
    }



    public function findOneBy(array $criteria): ?Post
    {
            // Construction dynamique de la clause WHERE
            $queryParts = [];
        foreach ($criteria as $key => $value) {
            $queryParts[] = "posts.$key = :$key";
        }
            $queryString = implode(' AND ', $queryParts);

            $req = ConnectDB::getPDO()->prepare(
                "SELECT posts.id, posts.title, posts.content, posts.createdAt, posts.chapo,
                posts.updatedAt, users.pseudo 
                FROM posts 
                INNER JOIN users ON posts.user_id = users.id
                WHERE $queryString"
            );


        foreach ($criteria as $key => $value) {
            $req->bindValue(":$key", $value);
        }

            $req->execute();
            $data = $req->fetch(\PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

            return new Post(
                id: (int)$data['id'],
                title: $data['title'],
                content: $data['content'],
                createdAt: $data['createdAt'],
                chapo: $data['chapo'],
                updatedAt: $data['updatedAt'],
                pseudo: $data['pseudo']
            );
    }




    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
            // Construction dynamique de la clause WHERE
            $queryParts = [];
        foreach ($criteria as $key => $value) {
            $queryParts[] = "$key = :$key";
        }
            $queryString = implode(' AND ', $queryParts);

            // Construction de la requête SQL
            $sql = "SELECT posts.id, posts.title, posts.content, posts.createdAt, posts.chapo,
                    posts.updatedAt, users.pseudo 
                    FROM posts 
                    INNER JOIN users ON posts.user_id = users.id";

        if (!empty($queryParts)) {
            $sql .= " WHERE $queryString";
        }

            // Ajout de la clause ORDER BY
        if ($orderBy) {
            $orderParts = [];
            foreach ($orderBy as $column => $direction) {
                $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
                $orderParts[] = "$column $direction";
            }
            $sql .= " ORDER BY " . implode(', ', $orderParts);
        }

            // Ajout de la clause LIMIT
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

            // Ajout de la clause OFFSET
        if ($offset !== null) {
            $sql .= " OFFSET :offset";
        }

            $req = ConnectDB::getPDO()->prepare($sql);

            // Liaison des valeurs aux paramètres
        foreach ($criteria as $key => $value) {
            $req->bindValue(":$key", $value);
        }

        if ($limit !== null) {
            $req->bindValue(':limit', $limit, \PDO::PARAM_INT);
        }

        if ($offset !== null) {
            $req->bindValue(':offset', $offset, \PDO::PARAM_INT);
        }

            $req->execute();
            $results = $req->fetchAll(\PDO::FETCH_ASSOC);

        if (!$results) {
            return null;
        }

            // Création d'un tableau d'objets Post à partir des résultats
            $posts = [];
        foreach ($results as $data) {
            $posts[] = new Post(
                id: (int)$data['id'],
                title: $data['title'],
                content: $data['content'],
                createdAt: $data['createdAt'],
                chapo: $data['chapo'],
                updatedAt: $data['updatedAt'],
                pseudo: $data['pseudo']
            );
        }

            return $posts;
    }

    // public function create(Post $post): bool
    // {
    // //insertion d'un user dans la BDD
    // }

    // public function update(Post $post): bool
    // {
    // //modification d'un user dans la BDD
    // }

    // public function delete(User $user): bool
    // {
    // //supprime user dans la BDD
    // }
}
