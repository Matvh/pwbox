<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 3/5/18
 * Time: 19:00
 */

namespace SlimApp\Controller;


use DateTime;
use Psr\Container\ContainerInterface;
USE Slim\Http\Request;
use Slim\Http\Response;
use SlimApp\Model\Folder\Folder;
use SlimApp\Model\User;

class FolderController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $paramValue = $args['id'];

        $exit = $this->container->get('folder_repository')->selectChild($paramValue);
        $files = $this->container->get('file_repository')->select($paramValue);

        return $this->container->get('view')->render($response, 'home.twig', ['folders' => $exit, 'id_folder' => $paramValue, 'files' => $files]);
    }

    public function createFolder(Request $request, Response $response, array $args)
    {
        $folderName = $_POST['folder_name'];
        if ($args != null) {
            $id = $this->container->get('user_repository')->exist($_SESSION['email']);
            $exist = $this->container->get('folder_repository')->exist($folderName, $id[0]['id']);
            $paramValue = $args['id'];

            if($exist){
                return $response->withStatus(302)->withHeader("Location", "/folder/$paramValue");
            }
            $root = 0;
            $date = new DateTime('now');
            $folder = new Folder(1, $date, $date, $folderName, "path", $root, "false");
            $user = $this->container->get('user_repository')->getUsername($_SESSION['email']);
            $email = $_SESSION['email'];
            $user = new User(1, $user, $email, "hola", "miquel", "jeje", "lolo", $date, $date, 1, "02/02/02", null);
            $this->container->get('folder_repository')->create($folder, $user);
            $child = $this->container->get('folder_repository')->selectChildId($folderName);
            $this->container->get('folder_repository')->createChild($paramValue, $child[0]['id']);
            return $response->withStatus(302)->withHeader("Location", "/folder/$paramValue");

        }
        else {
            $root = 1;
            $date = new DateTime('now');
            $id = $this->container->get('user_repository')->exist($_SESSION['email']);
            $exist = $this->container->get('folder_repository')->exist($folderName, $id[0]['id']);

            if($exist){
                return $response->withStatus(302)->withHeader("Location", "/");
            }
            $folder = new Folder(1, $date, $date, $folderName, "path", $root, "false");
            $user = $this->container->get('user_repository')->getUsername($_SESSION['email']);
            $email = $_SESSION['email'];
            $user = new User(1, $user, $email, "hola", "miquel", "jeje", "lolo", $date, $date, 1, "02/02/02", null);
            $this->container->get('folder_repository')->create($folder, $user);
            $child = $this->container->get('folder_repository')->selectChildId($folderName);
            $this->container->get('folder_repository')->createChild($_POST['id_folder'], $child[0]['id']);

            return $response->withStatus(302)->withHeader("Location", "/");

        }



    }

     public function deleteFolder(Request $request, Response $response, array $args)
     {

         $paramValue = $args['id'];
         $parent = $this->container->get('folder_repository')->selectParent($paramValue)[0]['id_root_folder'];
         $this->container->get('folder_repository')->delete($paramValue);

         if($parent != null) {
             $id = $args['id'];
             return $response->withStatus(302)->withHeader("Location", "/folder/$parent");
         } else {
             return $response->withStatus(302)->withHeader("Location", "/");
         }






     }

    public function renameFolder(Request $request, Response $response, array $args)
    {







    }

}