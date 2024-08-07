<?php

declare(strict_types=1);

 
require_once __DIR__ . '/../vendor/autoload.php';
use App\Service\Request;
use App\Service\Session;
use App\Service\Router;

const APP_ENV  = 'dev';

if (APP_ENV === 'dev') {
    $whoops = new \Whoops\Run();
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
    $whoops->register();
}

$request = new Request($_GET, $_POST, $_FILES, $_SERVER);
$router = new Router($request);
$response = $router->run();
echo $response;
