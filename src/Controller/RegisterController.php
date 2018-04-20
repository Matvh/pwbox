<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 20/4/18
 * Time: 18:42
 */

namespace SlimApp\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;


class RegisterController
{

    protected $container;

    /**
     * HelloController constructor.
     * @param $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response){


        try {
            return $this->container->get('view')->render($response, 'register.twig');
        } catch (NotFoundExceptionInterface $e) {
        }
    }

}