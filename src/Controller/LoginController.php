<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 18/4/18
 * Time: 19:42
 */

namespace SlimApp\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LoginController
{
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
        if (isset($_SESSION['email'])){
            return $this->container->get('view')->render($response, 'home.twig', ['email' => $_SESSION['email']]);
        } else {
            return $this->container->get('view')->render($response, 'login.twig');
        }
    }
}