# blog

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/488b312a81ee4b34aefb38434234588a)](https://app.codacy.com/gh/LanchesThomas/blog/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)


## Installation
- Cloner le projet : 
```shell
git clone https://github.com/LanchesThomas/blog.git
```
- Installer les dépendances : 
```shell
composer install
```

## Installer la BDD :
- Modifier le fichier `ConnectDB.php` situé dans `src\Service\ConnectDB.php`. Remplacez `root` et `''` ainsi qu'éventuellement `localhost` (si nécéssaire) par vos identifiants de base de données locale :
```php
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
```
- Importez simplement le fichier `blog.sql`, présent à la racine du projet, dans votre base de données SQL locale. Si toutes les informations ont correctement été renseignées, la connexion devrait se faire automatiquement.

## Lancement :

- Lancer les serveurs PHP : 
```shell
PHP -S localhost:8000 -t public
```
- Aller à l'adresse : `http://localhost:8000/`
