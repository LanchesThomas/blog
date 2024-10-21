<?php

declare(strict_types=1);

namespace App\Service;

use PDO;
use Dotenv\Dotenv;

/**
 * Singleton class for managing the PDO connection to the MySQL database.
 *
 * Methods:
 * - getPDO(): Initializes and returns a single PDO instance, creating it if not already set. It loads environment variables and connects to the database using default MySQL settings.
 *
 * This ensures that only one database connection is established throughout the application's lifecycle.
 */


final class ConnectDB
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
