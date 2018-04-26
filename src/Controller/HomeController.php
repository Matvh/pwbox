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



        if (strlen($password) >= 6 && strlen($password)<= 12){

            $date = new DateTime('now');
            $user = new User(1,$email,$email,null, $date, $date, hash("sha256",$password), null, null, null, null, null);
            $exit = $this->container->get('user_repository')->login($user);

            var_dump($exit);

            if (empty($exit)){
                //TODO
            } else {
                if (($exit[0]['email'] == $email || $exit[0]['username'] == $email) && $exit[0]['active_account'] == "true") {

                    $_SESSION['email'] = $user->getEmail();
                    return $this->container->get('view')->render($response, 'home.twig', ['user' => $exit[0]]);
                } else {
                    if (($exit[0]['email'] == $email || $exit[0]['username'] == $email) && $exit[0]['active_account'] == "false") {
                        return $this->container->get('view')->render($response, 'home.twig',
                            ['user' => $exit[0], 'mensaje' => "Activa la cuenta, porfavor"]);

                    } else {
                        echo "lol";
                    }
                }
            }
        } else {
            echo "lel";
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