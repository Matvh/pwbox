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
use Slim\Flash\Messages;
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
            return $this->container->get('view')->render($response, 'home.html.twig');
        } else {
        }
    }

    public function indexAction(Request $request, Response $response) {

        if (!isset($_SESSION['email'])){
            //TODO landing page explicando de qué va esta cosa
            return $this->container->get('view')->render($response,'login.html.twig');

        } else {

            $path = $this->container->get('user_repository')->getProfilePic($_SESSION['email']);
            $username = $this->container->get('user_repository')->getUsername($_SESSION['email']);
            $folders = $this->container->get('folder_repository')->select($_SESSION['email']);

            $messages = $this->container->get('flash')->getMessages();

            //return $response->withStatus(302)->withHeader("Location", "/login");
            return $this->container->get('view')->render($response,'home.html.twig', ['email' => $_SESSION['email'],'pic' =>
                $path,'username' => $username, 'folders' => $folders, 'messages' => $messages]);
        }


    }

    public function validateSession(Request $request, Response $response){


        if(isset($_SESSION['email'])){

            $exit = $this->container->get('user_repository')->getActivate($_SESSION['email']);
            //miramos si la cuenta esta activada
            if($exit == "false") $mensaje = "Activa la cuenta, porfavor";
            else $mensaje = "";
            $messages = $this->container->get('flash')->getMessages();
            $hasParent = true;

            $path = $this->container->get('user_repository')->getProfilePic($_SESSION['email']);
            $username = $this->container->get('user_repository')->getUsername($_SESSION['email']);
            $folders = $this->container->get('folder_repository')->selectChild($_SESSION['folder_id']);
            $parentFolder = $this->container->get('folder_repository')->selectParent($_SESSION['folder_id']);
            $files = $this->container->get('file_repository')->select($_SESSION['folder_id']);
            $size = 1024 - ($this -> container -> get('user_repository')->getSize($_SESSION['email']));
            $sizepercent = ($size/1024) *100;

            $notifications = $this->container->get('notification_repository')->getNotifications($_SESSION['email']);

            if($parentFolder == null) {
                $hasParent = false;

                return $this->container->get('view')->render($response,'home.html.twig', ['username' => $username, 'folders' => $folders, 'path' => $path,
                    'files' => $files, 'messages' => $messages, 'mensaje' => $mensaje, 'size' => $size, 'sizepercent' => $sizepercent, 'hasParent'
                    => $hasParent, 'notifications' => $notifications]);;
            } else {
                return $this->container->get('view')->render($response,'home.html.twig', ['username' => $username, 'folders' => $folders, 'path' => $path,
                    'files' => $files, 'messages' => $messages, 'mensaje' => $mensaje, 'size' => $size, 'sizepercent' => $sizepercent, 'hasParent'
                    => $hasParent, 'parent_folder' => $parentFolder[0]['id_root_folder'], 'notifications' => $notifications]);
            }


        } else {
            return $response->withStatus(302)->withHeader("Location", "/login");

        }
    }

}