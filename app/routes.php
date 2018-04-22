<?php

/*$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    return $this->view->render($response,'hello.twig',['name' => $name]);
});*/


$app->add('SlimApp\Controller\Middleware\SessionMiddleware');

//$app->get('/hello/{name}', 'SlimApp\Controller\HelloController');
$app->get('/hello/{name}', 'SlimApp\Controller\HelloController');
//->add('SlimApp\Controller\Middleware\ExampleMiddleware');
//->add('SlimApp\Controller\Middleware\UserLoggedMiddleware');

$app->post('/user','SlimApp\Controller\PostUserController');
$app->get('/login','SlimApp\Controller\LoginController');

$app->get('/register', 'SlimApp\Controller\RegisterController');
$app->post('/register', 'SlimApp\Controller\RegisterController:validateData');


$app->post('/home','SlimApp\Controller\HomeController:indexAction');
