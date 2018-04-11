<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;
require_once __DIR__. '/../app/dependencies.php';
require_once __DIR__. '/../app/routes.php';
$app->run();