<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 22/4/18
 * Time: 20:20
 */

namespace SlimApp\Controller;


use DateTime;
use Doctrine\DBAL\Driver\PDOException;
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

        if(!preg_match('/@/', $email)){


            $exit = $this->container->get('user_repository')->getEmail($email);
            if ($exit){
                $email = $exit[0]['email'];
            } else {
                return $this->container->get('view')->render($response, 'login.twig', ['mensaje' => "El usuario no existe"]);

            }


        }





        $date = new DateTime('now');
        $user = new User(1,$email,$email,null, $date, $date, hash("sha256",$password), null, null, null, null, null);
        $exit = $this->container->get('user_repository')->login($user);

        //var_dump($exit);

        if (empty($exit)){
            return $this->container->get('view')->render($response, 'login.twig', ['mensaje' => "Contraseña erronea"]);

        } else {

            $_SESSION['email'] = $email;
            $path = $this->container->get('user_repository')->getProfilePic($email);
            $username = $this->container->get('user_repository')->getUsername($email);


            if (($exit[0]['email'] == $email || $exit[0]['username'] == $email) && $exit[0]['active_account'] == "true") {

                return $this->container->get('view')->render($response, 'home.twig', ['email' => $_SESSION['email'],'pic' => $path,'username' => $username]);
            } else {
                if (($exit[0]['email'] == $email || $exit[0]['username'] == $email) && $exit[0]['active_account'] == "false") {
                    return $this->container->get('view')->render($response, 'home.twig',
                        ['email' => $_SESSION['email'],'pic' => $path,'username' => $username, 'mensaje' => "Activa la cuenta, porfavor"]);

                } else {
                    echo "lol";
                }
            }
        }


    }

    public function validateSession(Request $request, Response $response){

        if (isset($_GET['email'])){
            $path = $this->container->get('user_repository')->getProfilePic($_GET['email']);
            $username = $this->container->get('user_repository')->getUsername($_GET['email']);
            return $this->container->get('view')->render($response, 'home.twig', ['email' => $_GET['email'], 'pic' => $path, 'username' => $username]);
        }else{
            if(isset($_SESSION['email'])){
                $path = $this->container->get('user_repository')->getProfilePic($_SESSION['email']);
                $username = $this->container->get('user_repository')->getUsername($_SESSION['email']);
                return $this->container->get('view')->render($response, 'home.twig', ['email' => $_SESSION['email'],'pic' => $path,'username' => $username]);
            } else {
                return $this->container->get('view')->render($response, 'login.twig');

            }
        }
    }

}