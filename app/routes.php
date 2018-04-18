<?php

/*$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    return $this->view->render($response,'hello.twig',['name' => $name]);
});*/

//$app->get('/hello/{name}', 'SlimApp\Controller\HelloController');
$app->get('/hello/{name}', 'SlimApp\Controller\HelloController:indexAction'
)->add('SlimApp\Controller\Middleware\ExampleMiddleware');

$app->post('/user','SlimApp\Controller\PostUserController');
$app->get('/home','SlimApp\Controller\HomeController');

