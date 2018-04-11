<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


/*$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    return $this->view->render($response,'hello.twig',['name' => $name]);
});*/

//$app->get('/hello/{name}', 'SlimApp\Controller\HelloController');
$app->get('/hello/{name}', 'SlimApp\Controller\HelloController:indexAction'
)->add('SlimApp\Controller\Middleware\ExampleMiddleware');
