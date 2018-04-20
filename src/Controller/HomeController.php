<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 18/4/18
 * Time: 19:42
 */

namespace SlimApp\Controller;

use DateTime;
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

        $resul = $request->getParsedBody();
        $email = $resul['email'];
        $password = $resul['password'];

        if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 6 && strlen($password)<= 12){

            $date = new DateTime('now');
            $user = new User(1,'miquel',$email,$password, $date, $date);
            $exit = $this->container->get('user_repository')->login($user);

            if($exit){
                return $this->container->get('view')->render($response, 'login.twig');
            } else {
                //TODO mensaje de error
            }
        } else {
            //TODO mensaje de error
        }

    }

}