<?php

namespace SlimApp\Controller;

 use Psr\Container\ContainerInterface;
 use Psr\Http\Message\ServerRequestInterface as Request;
 use Psr\Http\Message\ResponseInterface as Response;

 class HelloController {

     protected $container;

     /**
      * HelloController constructor.
      * @param $container
      */
     public function __construct($container) {
         $this->container = $container;
     }

     public function __invoke(Request $request, Response $response, array $args)
     {
         if (!isset($_SESSION['counter'])){
             $_SESSION['counter'] = 1;
         } else {
             $_SESSION['counter'] += 1;
         }
         $name = $args['name'];
         //$this ->container->get('test');



         return $this->container->get('view')->render($response,'hello.twig',['name' => $name, 'counter' => $_SESSION['counter']]);
     }

 }