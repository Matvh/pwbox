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

    public function __invoke(Request $request, Response $response)
    {

        $_SESSION['folder_id'] = $_POST['id_folder'];
        var_dump($_SESSION['folder_id'], $_POST['id_folder']);exit();

        return $response->withStatus(302)->withHeader("Location", "/home");

    }

    public function createFolder(Request $request, Response $response)
    {

        $folderName = $_POST['folder_name'];

        $id = $this->container->get('user_repository')->exist($_SESSION['email']);
        $exist = $this->container->get('folder_repository')->exist($folderName, $id[0]['id']);
        $paramValue = $_SESSION['folder_id'];

        if($exist){
            $this->container->get('flash')->addMessage('carpeta_error', "Error, la carpeta con ese nombre ya existe");
            return $response->withStatus(302)->withHeader("Location", "/home");
        }


        $date = new DateTime('now');
        $folder = new Folder(1, $date, $date, $folderName, "path", "false");
        $user = $this->container->get('user_repository')->getUsername($_SESSION['email']);
        $email = $_SESSION['email'];
        $user = new User(1, $user, $email, "", "", "", "", $date, $date, 1, "", null);
        $this->container->get('folder_repository')->create($folder, $user);
        $child = $this->container->get('folder_repository')->selectChildId($folderName);
        $this->container->get('folder_repository')->createChild($paramValue, $child[0]['id']);
        return $response->withStatus(302)->withHeader("Location", "/home");

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
        $id_folder = $_POST['id_folder'];
        $newName = $_POST['folder_name'];

        $exit = $this->container->get('folder_repository')->rename($newName, $id_folder);

        if($exit)
        {

            $this->container->get('flash')->addMessage('carpeta_error', "Error, la carpeta con ese nombre ya existe");
            return $response->withStatus(302)->withHeader("Location", "/home");


        } else{

            return $response->withStatus(302)->withHeader("Location", "/home");

        }







    }

}