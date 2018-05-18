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
        $MAX_AV_SIZE = 1073741824;

        if(isset($_SESSION['email'])){

            $exit = $this->container->get('user_repository')->getActivate($_SESSION['email']);
            //miramos si la cuenta esta activada
            if($exit == "false") $mensaje = "Please, activate your account";
            else $mensaje = "";
            $messages = $this->container->get('flash')->getMessages();
            $hasParent = true;
            $id = $this->container->get('user_repository')->getID($_SESSION['email']);
            $path = $this->container->get('user_repository')->getProfilePic($_SESSION['email']);
            $username = $this->container->get('user_repository')->getUsername($_SESSION['email']);
            $folders = $this->container->get('folder_repository')->selectChild($_SESSION['folder_id']);
            $parentFolder = $this->container->get('folder_repository')->selectParent($_SESSION['folder_id']);
            $files = $this->container->get('file_repository')->select($_SESSION['folder_id']);
            $size = $MAX_AV_SIZE - ($this -> container -> get('user_repository')->getSize($_SESSION['email']));
            $sizepercent = ($size/$MAX_AV_SIZE) *100;

            $sizeUser = $this->convertToReadableSize($size);

            $notifications = $this->container->get('notification_repository')->getNotifications($id);
            //var_dump($notifications);exit();

            if($parentFolder == null) {
                $hasParent = false;

                return $this->container->get('view')->render($response,'home.html.twig', ['username' => $username, 'folders' => $folders, 'path' => $path,
                    'files' => $files, 'messages' => $messages, 'mensaje' => $mensaje, 'size' => $sizeUser, 'sizepercent' => $sizepercent, 'hasParent'
                    => $hasParent, 'notifications' => $notifications]);
            } else {
                return $this->container->get('view')->render($response,'home.html.twig', ['username' => $username, 'folders' => $folders, 'path' => $path,
                    'files' => $files, 'messages' => $messages, 'mensaje' => $mensaje, 'size' => $sizeUser, 'sizepercent' => $sizepercent, 'hasParent'
                    => $hasParent, 'parent_folder' => $parentFolder[0]['id_root_folder'], 'notifications' => $notifications]);
            }


        } else {
            return $response->withStatus(302)->withHeader("Location", "/login");

        }
    }

    function convertToReadableSize($size)
    {
        if($size <= 0) return '0MB';
        $base = log($size) / log(1024);
        $suffix = array("", "KB", "MB", "GB", "TB");
        $f_base = floor($base);
        return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
    }

}