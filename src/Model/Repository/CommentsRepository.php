<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Comments;
use App\Service\ConnectDB;
use App\Model\Entity\Post;

final readonly class CommentsRepository
{
    public function __construct()
    {
    }

    public function findAll(): array
    {
        $sql = '
            SELECT 
                comments.id, 
                comments.statut, 
                comments.createdAt, 
                comments.content, 
                posts.title AS post_title, 
                users.pseudo AS user_pseudo 
            FROM 
                comments
            INNER JOIN 
                posts ON comments.post_id = posts.id
            INNER JOIN 
                users ON comments.user_id = users.id
        ';

        $req = ConnectDB::getPDO()->prepare($sql);
        $req->execute();
        $results = $req->fetchAll(\PDO::FETCH_ASSOC);

        $comments = [];
        foreach ($results as $data) {
            $comments[] = new Comments(
                id: (int)$data['id'],
                statut: $data['statut'],
                createdAt: $data['createdAt'],
                content: $data['content'],
                post: $data['post_title'],
                pseudo: $data['user_pseudo']
            );
        }

        return $comments;
    }




    public function find(int $id): ?Comments
    {
        // Préparer la requête pour sélectionner le commentaire avec les informations associées
        $sql = '
            SELECT 
                comments.id, 
                comments.statut, 
                comments.createdAt, 
                comments.content, 
                posts.title AS post_title, 
                users.pseudo AS user_pseudo 
            FROM 
                comments
            INNER JOIN 
                posts ON comments.post_id = posts.id
            INNER JOIN 
                users ON comments.user_id = users.id
            WHERE 
                comments.id = :id
        ';

        $stmt = ConnectDB::getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Si aucun commentaire n'est trouvé, retourner null
        if ($data === false) {
            return null;
        }

        // Hydrater et retourner l'objet Comments
        return new Comments(
            id: (int)$data['id'],
            statut: $data['statut'],
            createdAt: $data['createdAt'],
            content: $data['content'],
            post: $data['post_title'],
            pseudo: $data['user_pseudo']
        );
    }



    public function findOneBy(array $criteria): ?Comments
    {
        // Construction dynamique de la clause WHERE
        $queryParts = [];
        foreach ($criteria as $key => $value) {
            $queryParts[] = "comments.$key = :$key";
        }
        $queryString = implode(' AND ', $queryParts);

        // Préparer la requête SQL avec jointure sur les tables posts et users
        $sql = "
            SELECT 
                comments.id, 
                comments.statut, 
                comments.createdAt, 
                comments.content, 
                posts.id AS post_id, 
                users.pseudo AS user_pseudo 
            FROM 
                comments
            INNER JOIN 
                posts ON comments.post_id = posts.id
            INNER JOIN 
                users ON comments.user_id = users.id
            WHERE 
                $queryString
        ";

        $req = ConnectDB::getPDO()->prepare($sql);

        // Liaison des valeurs aux paramètres
        foreach ($criteria as $key => $value) {
            $req->bindValue(":$key", $value);
        }

        $req->execute();
        $data = $req->fetch(\PDO::FETCH_ASSOC);

        // Si aucun commentaire n'est trouvé, retourner null
        if (!$data) {
            return null;
        }

        // Hydrater et retourner l'objet Comments
        return new Comments(
            id: (int)$data['id'],
            statut: $data['statut'],
            createdAt: $data['createdAt'],  // Assurez-vous que le nom de la colonne correspond
            content: $data['content'],
            post: $data['post_id'],
            pseudo: $data['user_pseudo']
        );
    }



    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        // Construction dynamique de la clause WHERE
        $queryParts = [];
        foreach ($criteria as $key => $value) {
            $queryParts[] = "comments.$key = :$key";
        }
        $queryString = implode(' AND ', $queryParts);

        // Construction de la requête SQL avec jointures
        $sql = "
            SELECT 
                comments.id, 
                comments.statut, 
                comments.createdAt, 
                comments.content, 
                posts.id AS post_id, 
                users.pseudo AS user_pseudo 
            FROM 
                comments
            INNER JOIN 
                posts ON comments.post_id = posts.id
            INNER JOIN 
                users ON comments.user_id = users.id
        ";

        if (!empty($queryParts)) {
            $sql .= " WHERE $queryString";
        }

        // Ajout de la clause ORDER BY si nécessaire
        if ($orderBy) {
            $orderParts = [];
            foreach ($orderBy as $column => $direction) {
                $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';  // Validation de la direction
                $orderParts[] = "$column $direction";
            }
            $sql .= " ORDER BY " . implode(', ', $orderParts);
        }

        // Ajout de la clause LIMIT si nécessaire
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        // Ajout de la clause OFFSET si nécessaire
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

        // Création d'un tableau d'objets Comments à partir des résultats
        $comments = [];
        foreach ($results as $data) {
            $comments[] = new Comments(
                id: (int)$data['id'],
                statut: $data['statut'],
                createdAt: $data['createdAt'],  // Assurez-vous que le nom de la colonne correspond
                content: $data['content'],
                post: $data['post_id'],
                pseudo: $data['user_pseudo']       // Nom du post récupéré par la jointure
            );
        }

        return $comments;
    }



    public function create(Post $post, string $comments): void
    {
        $postId = $post->getId();
        $statut = 1;
        $createdAt = (new \DateTime())->format('Y-m-d H:i:s');

        $newComment = new Comments(
            id: null,
            statut: $statut,
            createdAt: $createdAt,
            content: $comments,
            post: $postId,
            pseudo: null
        );

        $sql = '
        INSERT INTO comments (statut, createdAt, content, post_id, user_id)
        VALUES (:statut, :createdAt, :content, :post_id, :user_id)
    ';

        $stmt = ConnectDB::getPDO()->prepare($sql);

        $stmt->bindValue(':statut', $newComment->getStatut(), \PDO::PARAM_INT);
        $stmt->bindValue(':createdAt', $newComment->getCreatedAt(), \PDO::PARAM_STR);
        $stmt->bindValue(':content', $newComment->getContent(), \PDO::PARAM_STR);
        $stmt->bindValue(':post_id', $newComment->getPost(), \PDO::PARAM_INT);
        $stmt->bindValue(':user_id', 1);

        $stmt->execute();
    }

    // public function update(User $user): bool
    // {
    // //modification d'un user dans la BDD
    // }

    // public function delete(User $user): bool
    // {
    // //supprime user dans la BDD
    // }
}
