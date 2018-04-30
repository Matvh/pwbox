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
         if (!isset($_SESSION['email'])){
             //TODO landing page explicando de qué va esta cosa
             return $this->container->get('view')->render($response,'login.twig');

         } else {
             var_dump($this->container->get('user_repository')->getSize($_SESSION['email']));
             return $this->container->get('view')->render($response,'home.twig');
         }
         $name = $args['name'];
         //$this ->container->get('test');




     }

 }