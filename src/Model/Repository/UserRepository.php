<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\User;
use App\Service\ConnectDB;

final readonly class UserRepository
{
    public function __construct()
    {
    }

    public function findAll(): array
    {
        $req = ConnectDB::getPDO()->prepare('SELECT * FROM users');
        $req->execute();
        $results = $req->fetchAll(\PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $data) {
            $users[] = new User(
                id: (int)$data['id'],
                pseudo: $data['pseudo'],
                mail: $data['mail'],
                password: $data['password'],
                role: $data['role']
            );
        }

        return $users;
    }


    public function find(string $mail): ?User
    {
        $stmt = ConnectDB::getPDO()->prepare('SELECT * FROM users WHERE mail = :mail');
        $stmt->bindValue(':mail', $mail, \PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new User(
            id: (int)$data['id'],
            pseudo: $data['pseudo'],
            mail: $data['mail'],
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

        $sql = "SELECT * FROM users WHERE $queryString";
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
            mail: $data['mail'],
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
                mail: $data['mail'],
                password: $data['password'],
                role: $data['role']
            );
        }

        return $users;
    }


    public function create(string $mail, string $pseudo, string $password): void
    {

        $newUser = new User(
            id: null,
            mail: $mail,
            pseudo: $pseudo,
            password: $password,
            role: 'editor'
        );

        $sql = '
        INSERT INTO users (mail, pseudo, password, role)
        VALUES (:mail, :pseudo, :password, :role)
        ';

        $stmt = ConnectDB::getPDO()->prepare($sql);
        $stmt->bindValue(':mail', $newUser->getMail(), \PDO::PARAM_STR);
        $stmt->bindValue(':pseudo', $newUser->getPseudo(), \PDO::PARAM_STR);
        $stmt->bindValue(':password', $newUser->getPassword(), \PDO::PARAM_STR);
        $stmt->bindValue(':role', $newUser->getRole(), \PDO::PARAM_STR);

        $stmt->execute();
    }

    public function update(int $id, string $role): void
    {
        $sql = '
        UPDATE users
        SET role = :role
        WHERE id = :id
        ';

        $stmt = ConnectDB::getPDO()->prepare($sql);

        $stmt->bindValue(':role', $role, \PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        $stmt->execute();
    }

    public function delete(int $id): void
    {
        $sql = '
        DELETE FROM users
        WHERE id = :id
        ';

        $stmt = ConnectDB::getPDO()->prepare($sql);

        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        $stmt->execute();
    }
}
