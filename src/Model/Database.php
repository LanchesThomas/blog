<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

class ConnectDB
{
    private static $pdo = null;

    public static function getPDO()
    {
        require_once '../config/config.php';
        if (self::$pdo === null) {
            self::$pdo = new PDO(DB_HOST, DB_USER, DB_PASS, DB_OPTIONS);
            self::$pdo->exec('SET NAMES UTF8');
        }
        return self::$pdo;
    }
}
