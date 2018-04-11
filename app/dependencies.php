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

$container['test'] = function (){
   echo '<br> hola estoy en dependencies';
};
