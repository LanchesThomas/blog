<?php

declare(strict_types=1);

namespace App\Model;

use App\Service\ConnectDB as ModelConnectDB;

class MainModel
{
    public function read($statement)
    {
        $req = ModelConnectDB::getPDO()->prepare($statement);
        $req->execute();
        return $req->fetch();
    }

    public function readAll($statement)
    {
        $req = ModelConnectDB::getPDO()->prepare($statement);
        $req->execute();
        return $req->fetchAll();
    }
}
