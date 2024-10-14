<?php

declare(strict_types=1);


namespace App\Service;

use PDO;
use Dotenv\Dotenv;
// Charger le fichier .env



class ConnectDB
{
    private static $pdo = null;

    public static function getPDO()
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();    
        if (self::$pdo === null) {
            self::$pdo = new PDO('mysql:host=localhost;dbname=blog;charset=UTF8', 'root', '', array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            self::$pdo->exec('SET NAMES UTF8');
        }
        return self::$pdo;
    }
}
