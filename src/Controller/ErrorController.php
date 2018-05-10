<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 1/5/18
 * Time: 18:20
 */

namespace SlimApp\Controller;


use http\Env\Response;
use Slim\Http\Request;

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