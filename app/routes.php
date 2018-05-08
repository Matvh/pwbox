<?php

/*$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    return $this->view->render($response,'hello.html.twig',['name' => $name]);
});*/


$app->add('SlimApp\Controller\Middleware\SessionMiddleware');

//$app->get('/hello/{name}', 'SlimApp\Controller\HelloController');
$app->get('/hello/{name}', 'SlimApp\Controller\HelloController');
//->add('SlimApp\Controller\Middleware\ExampleMiddleware');
//->add('SlimApp\Controller\Middleware\UserLoggedMiddleware');

$app->post('/user','SlimApp\Controller\PostUserController');
$app->get('/login','SlimApp\Controller\LoginController');
$app->post('/login','SlimApp\Controller\LoginController:login');


$app->get('/register', 'SlimApp\Controller\RegisterController');
$app->post('/register', 'SlimApp\Controller\RegisterController:validateData');

$app->get('/home','SlimApp\Controller\HomeController:validateSession');
$app->post('/home','SlimApp\Controller\HomeController:indexAction');


$app->get('/','SlimApp\Controller\HomeController:validateSession');
$app->get('/add','SlimApp\Controller\CreateFolderController');


$app->get('/file', 'SlimApp\Controller\FileController:showFormAction');
$app->post('/file', 'SlimApp\Controller\FileController:uploadFileAction');

$app->get('/activate', 'SlimApp\Controller\ActivateAccountController:activateAction');


$app->get('/profile', 'SlimApp\Controller\MyAccountController');
$app->post('/profile', 'SlimApp\Controller\MyAccountController:updateData');

$app->get('/error', 'SlimApp\Controller\ErrorController');

$app->get('/logout', 'SlimApp\Controller\LogoutController');

$app->get('/resendemail', 'SlimApp\Controller\ResendActivateController');

$app->post('/folder', 'SlimApp\Controller\FolderController');
$app->post('/create-folder', 'SlimApp\Controller\FolderController:createFolder');

$app->get('/delete/{id}', 'SlimApp\Controller\FolderController:deleteFolder');
$app->get('/rename/{id}', 'SlimApp\Controller\FolderController:renameFolder');


$app->post('/deleteAccount', 'SlimApp\Controller\MyAccountController:deleteUser');
