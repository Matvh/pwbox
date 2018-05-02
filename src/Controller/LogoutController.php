<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 2/5/18
 * Time: 18:39
 */

namespace SlimApp\Controller;


use Slim\Http\Request;
use Slim\Http\Response;

class LogoutController
{

    protected $container;

    /**
     * HelloController constructor.
     * @param $container
     */
    public function __construct($container) {
        $this->container = $container;
    }

    public function __invoke(Request $request  ,Response $response)
    {
        session_destroy();
        return $response->withStatus(302)->withHeader("Location", "/login");

    }
}