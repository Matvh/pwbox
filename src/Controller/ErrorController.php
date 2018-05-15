<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 1/5/18
 * Time: 18:20
 */

namespace SlimApp\Controller;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ErrorController
{

    protected $container;

    /**
     * HelloController constructor.
     * @param $container
     */
    public function __construct($container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'error.html.twig');
    }
}