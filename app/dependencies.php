<?php

$container = $app->getContainer();
$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../src/view/templates', [
       //'cache' => __DIR__ . '/../var/cache/'
    ]);
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};

$container['doctrine'] = function ($container){
   $config = new \Doctrine\DBAL\Configuration();
   $conn =  \Doctrine\DBAL\DriverManager::getConnection(
       $container->get('settings')['database'],$config
   );
   return $conn;
};

$container['user_repository'] = function ($container){
    $repository = new \SlimApp\Implementations\DoctrineUserRepository(
        $container->get('doctrine')
    );
    return $repository;
};

$container['folder_repository'] = function ($container){
    $repository = new \SlimApp\Implementations\DoctrineFolderRepository(
        $container->get('doctrine')
    );
    return $repository;
};


$container['post_user_use_case'] = function ($container){
    $useCase = new SlimApp\Model\UseCase\PostUserCase(
        $container->get('user_repository')
    );
    return $useCase;
};


$container['flash'] = function (){
    return new \Slim\Flash\Message();
};

