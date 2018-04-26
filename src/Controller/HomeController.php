<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 22/4/18
 * Time: 20:20
 */

namespace SlimApp\Controller;


use DateTime;
use Slim\Http\Request;
use Slim\Http\Response;
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
        $email = $args['email'];

        $exit = $this->container->get('user_repository')->remove($email);

        if($exit){
            shell_exec("rm -rf /home/vagrant/users/$email");

            return $this->container->get('view')->render($response, 'home.twig');
        } else {

        }
    }

    public function indexAction(Request $request, Response $response) {

        $resul = $request->getParsedBody();
        $email = $resul['email'];
        $password = $resul['password'];



        if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 6 && strlen($password)<= 12){

            $date = new DateTime('now');
            $user = new User(1,'miquel',$email,null, $date, $date, hash("sha256",$password), null, null, null, null, null);
            $exit = $this->container->get('user_repository')->login($user);

            if($exit && $exit['active_account'] == "true"){

                $_SESSION['email'] = $user->getEmail();
                return $this->container->get('view')->render($response, 'login.twig', ['email' => $email]);
            } else if ($exit && $exit['active_account'] == "false"){
                echo "no has activado tu cuenta";
            }
        } else {
            //TODO mensaje de error
        }

    }

    public function validateSession(Request $request, Response $response){
        if(isset($_SESSION['email'])){
            return $this->container->get('view')->render($response, 'login.twig', ['email' => $_SESSION['email']]);
        } else {
            return $this->container->get('view')->render($response, 'home.twig');

        }
    }

}