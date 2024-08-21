<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\User;
use App\Service\ConnectDB;

final readonly class UserRepository
{
    public function __construct(private ConnectDB $database)
    {
    }

    public function findAll(): array
    {
        $req = ConnectDB::getPDO()->prepare('SELECT * FROM `user`');
        $req->execute();
        $results = $req->fetchAll(\PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $data) {
            $users[] = new User(
                id: (int)$data['id'],
                pseudo: $data['pseudo'],
                email: $data['email'],
                password: $data['password'],
                role: $data['role']
            );
        }

        return $users;
    }


    public function find(int $id): ?User
    {
        $stmt = $this->database->getPDO()->prepare('SELECT * FROM user WHERE id = :id');
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new User(
            id: (int)$data['id'],
            pseudo: $data['pseudo'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role']
        );
    }


    public function findOneBy(array $criteria): ?User
    {
        // Construction dynamique de la clause WHERE
        $queryParts = [];
        foreach ($criteria as $key => $value) {
            $queryParts[] = "$key = :$key";
        }
        $queryString = implode(' AND ', $queryParts);

        $sql = "SELECT * FROM user WHERE $queryString";
        $req = ConnectDB::getPDO()->prepare($sql);

        foreach ($criteria as $key => $value) {
            $req->bindValue(":$key", $value);
        }

        $req->execute();
        $data = $req->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new User(
            id: (int)$data['id'],
            pseudo: $data['pseudo'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role']
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
        $sql = "SELECT * FROM user";

        if (!empty($queryParts)) {
            $sql .= " WHERE $queryString";
        }

        // Ajout de la clause ORDER BY
        if ($orderBy) {
            $orderParts = [];
            foreach ($orderBy as $column => $direction) {
                $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';  // Validation de la direction
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

        // Création d'un tableau d'objets User à partir des résultats
        $users = [];
        foreach ($results as $data) {
            $users[] = new User(
                id: (int)$data['id'],
                pseudo: $data['pseudo'],
                email: $data['email'],
                password: $data['password'],
                role: $data['role']
            );
        }

        return $users;
    }


    // public function create(User $user): bool
    // {
    // //insertion d'un user dans la BDD
    // }

    // public function update(User $user): bool
    // {
    // //modification d'un user dans la BDD
    // }

    // public function delete(User $user): bool
    // {
    // //supprime user dans la BDD
    // }
}
