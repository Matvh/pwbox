<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 1/5/18
 * Time: 16:28
 */

namespace SlimApp\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class MyAccountController
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
        $email = $_SESSION['email'];
        $exit = $this->container->get('user_repository')->exist($email);


        return $this->container->get('view')->render($response,'myAccount.twig', ['user' => $exit[0]]);
    }
}