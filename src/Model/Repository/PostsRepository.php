<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\User;
use App\Model\Entity\Post;
use App\Service\ConnectDB;
use App\Service\Session;

final readonly class PostsRepository
{
    public function __construct(private Session $session)
    {
    }

    public function findAll(): array
    {
        $req = ConnectDB::getPDO()->prepare('
            SELECT posts.id, posts.title, posts.content, posts.createdAt, posts.chapo,
                   posts.updatedAt, posts.statut, users.pseudo
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
                pseudo: $data['pseudo'],
                userId: $data['id'],
                statut: $data['statut']
            );
        }

        return $posts;
    }




    public function find(int $id): ?Post
    {
            $req = ConnectDB::getPDO()->prepare(
                'SELECT posts.id, posts.title, posts.content, posts.createdAt, posts.chapo,
                posts.updatedAt, posts.statut, users.pseudo 
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
                pseudo: $data['pseudo'],
                userId: $data['id'],
                statut: $data['statut']
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
                posts.updatedAt, posts.statut, users.pseudo 
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
                pseudo: $data['pseudo'],
                userId: $data['id'],
                statut: $data['statut']
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
                    posts.updatedAt, posts.statut, users.pseudo 
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
                pseudo: $data['pseudo'],
                userId: $data['id'],
                statut: $data['statut']
            );
        }

            return $posts;
    }

    public function create($title, $chapo, $content): void
    {
        $createdAt = (new \DateTime())->format('Y-m-d H:i:s');
        $newPost = new Post(
            id: null,
            title: $title,
            content: $content,
            createdAt: $createdAt,
            chapo: $chapo,
            updatedAt: $createdAt,
            pseudo: $this->session->getUser()['pseudo'],
            userId: $this->session->getUser()['userId'],
            statut: 'published'
        );

        $sql = '
        INSERT INTO posts (title, chapo, content, createdAt, updatedAt, statut, user_id)
        VALUES (:title, :chapo, :content, :createdAt, :updatedAt, :statut, :user_id)
        ';

        $stmt = ConnectDB::getPDO()->prepare($sql);
        $stmt->bindValue(':title', $newPost->getTitle(), \PDO::PARAM_STR);
        $stmt->bindValue(':chapo', $newPost->getChapo(), \PDO::PARAM_STR);
        $stmt->bindValue(':content', $newPost->getContent(), \PDO::PARAM_STR);
        $stmt->bindValue(':createdAt', $newPost->getCreatedAt(), \PDO::PARAM_STR);
        $stmt->bindValue(':updatedAt', $newPost->getUpdatedAt(), \PDO::PARAM_STR);
        $stmt->bindValue(':statut', $newPost->getStatut(), \PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $newPost->getUserId(), \PDO::PARAM_INT);

        $stmt->execute();
    }

    public function update(?int $id, ?string $statut = null, ?string $title = null, ?string $chapo = null, ?string $content = null, ?int $userId = null): void
    {
        $fields = [];

        if ($statut !== null) {
            $fields[] = 'statut = :statut';
        }
        if ($title !== null) {
            $fields[] = 'title = :title';
        }
        if ($chapo !== null) {
            $fields[] = 'chapo = :chapo';
        }
        if ($content !== null) {
            $fields[] = 'content = :content';
        }
        if ($userId !== null) {
            $fields[] = 'user_id = :user_id';
        }

    // Si aucun champ à mettre à jour, on quitte la fonction
        if (empty($fields)) {
            return;
        }

    // Construction de la requête SQL dynamique
        $sql = 'UPDATE posts SET ' . implode(', ', $fields) . ' WHERE id = :id';

    // Préparation de la requête
        $stmt = ConnectDB::getPDO()->prepare($sql);

    // Liaison des valeurs pour les champs non null
        if ($statut !== null) {
            $stmt->bindValue(':statut', $statut, \PDO::PARAM_STR);
        }
        if ($title !== null) {
            $stmt->bindValue(':title', $title, \PDO::PARAM_STR);
        }
        if ($chapo !== null) {
            $stmt->bindValue(':chapo', $chapo, \PDO::PARAM_STR);
        }
        if ($content !== null) {
            $stmt->bindValue(':content', $content, \PDO::PARAM_STR);
        }
        if ($userId !== null) {
            $stmt->bindValue(':user_id', $userId, \PDO::PARAM_STR);
        }

    // Liaison de l'ID
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

    // Exécution de la requête
        $stmt->execute();
    }




    public function delete(int $id): void
    {
        $sql = '
        DELETE FROM posts
        WHERE id = :id
        ';

        $stmt = ConnectDB::getPDO()->prepare($sql);

        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        $stmt->execute();
    }
}
