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
use SlimApp\Model\User;

class HomeController
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
        //$name = $args['name'];
        //$this ->container->get('test');
        return $this ->container->get('view')->render($response,'home.twig');
    }
    public function indexAction(Request $request, Response $response) {

        //TODO filtrar i validar
        $resul = $request->getParsedBody();
        $email = $resul['email'];
        $password = $resul['password'];


        $user = new User(1,'miquel',$email,$password, time(), time());
        $exit = $this->container->get('user_repository')->login($user);

        if($exit){
            return $this->container->get('view')->render($response, 'login.twig');
        } else {
            //TODO mensaje de error
        }

    }

}