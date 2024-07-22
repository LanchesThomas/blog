<?php

declare(strict_types=1);

namespace App\Model;

class MainModel
{
    public function read($statement)
    {
        $req = ConnectDB::getPDO()->prepare($statement);
        $req->execute();
        return $req->fetch();
    }

    public function readAll($statement)
    {
        $req = ConnectDB::getPDO()->prepare($statement);
        $req->execute();
        return $req->fetchAll();
    }
}
