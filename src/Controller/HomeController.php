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

        if (!isset($_SESSION['email'])){
            //TODO landing page explicando de qué va esta cosa
            return $this->container->get('view')->render($response,'login.twig');

        } else {
            $path = $this->container->get('user_repository')->getProfilePic($_SESSION['email']);
            $username = $this->container->get('user_repository')->getUsername($_SESSION['email']);
            $folders = $this->container->get('folder_repository')->select($_SESSION['email']);

            //return $response->withStatus(302)->withHeader("Location", "/login");
            return $this->container->get('view')->render($response,'home.twig', ['email' => $_SESSION['email'],'pic' =>
                $path,'username' => $username, 'folders' => $folders]);
        }


    }

    public function validateSession(Request $request, Response $response){



        if (isset($_GET['email'])){
            $exit = $this->container->get('user_repository')->getActivate($_GET['email']);
            $username = $this->container->get('user_repository')->getUsername($_GET['email']);

            $foldersRoot = $this->container->get('folder_repository')->selectSuperRoot("root".$username)[0]['id'];

            if($exit == "false") $mensaje = "Activa la cuenta, porfavor";
            else $mensaje = "";
            $path = $this->container->get('user_repository')->getProfilePic($_GET['email']);
            $username = $this->container->get('user_repository')->getUsername($_GET['email']);
            $folders = $this->container->get('folder_repository')->select($_GET['email']);
            $files = $this->container->get('file_repository')->select($_POST['id_folder']);

            return $response->withStatus(307)->withHeader("Location", "/folder/$foldersRoot");

        }else{
            if(isset($_SESSION['email'])){
                $exit = $this->container->get('user_repository')->getActivate($_SESSION['email']);

                if($exit == "false") $mensaje = "Activa la cuenta, porfavor";
                else $mensaje = "";
                $path = $this->container->get('user_repository')->getProfilePic($_SESSION['email']);
                $username = $this->container->get('user_repository')->getUsername($_SESSION['email']);
                $folders = $this->container->get('folder_repository')->select($_SESSION['email']);

                $foldersRoot = $this->container->get('folder_repository')->selectSuperRoot("root".$username)[0]['id'];

                if($_POST != null) {
                    $files = $this->container->get('file_repository')->select($_POST['id_folder']);

                } else {
                    $files = $this->container->get('file_repository')->select($foldersRoot);
                }


                return $response->withStatus(307)->withHeader("Location", "/folder/$foldersRoot");


            } else {
                return $this->container->get('view')->render($response, 'login.twig');

            }
        }
    }

}