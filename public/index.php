<?php

declare(strict_types=1);
 
 
require_once __DIR__ . '/../vendor/autoload.php';

use App\Service\Router;

const APP_ENV  = 'dev';

if (APP_ENV === 'dev') {
    $whoops = new \Whoops\Run();
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
    $whoops->register();
}


$router = new Router();
$response = $router->run();
echo $response;

?>