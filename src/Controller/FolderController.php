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
        //var_dump($_SESSION['folder_id'], $_POST['id_folder']);

        //return $response->withStatus(302)->withHeader("Location", "/home");
        return $response->withJson(['ok'=>'ok'], 201);

    }

    public function createFolder(Request $request, Response $response)
    {

        $folderName = $_POST['folder_name'];

        //$id = $this->container->get('user_repository')->exist($_SESSION['email']);
       // $exist = $this->container->get('folder_repository')->exist($folderName, $id[0]['id']);
        $paramValue = $_SESSION['folder_id'];

        /*if($exist){
            $this->container->get('flash')->addMessage('carpeta_error', "Error, la carpeta con ese nombre ya existe");
            return $response->withStatus(302)->withHeader("Location", "/home");
        }*/


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

         $paramValue = $_POST['id_folder'];
         $parent = $this->container->get('folder_repository')->selectParent($paramValue)[0]['id_root_folder'];
         $this->container->get('folder_repository')->delete($paramValue);

         if($parent != null) {
             $id = $args['id'];
             return $response->withStatus(302)->withHeader("Location", "/home");
         } else {
             return $response->withStatus(302)->withHeader("Location", "/home");
         }

     }

    public function renameFolder(Request $request, Response $response, array $args)
    {
        $id_folder = $_POST['id_folder'];
        $newName = $_POST['folder_name'];

        $exit = $this->container->get('folder_repository')->rename($newName, $id_folder);

        if($exit)
        {

            $this->container->get('flash')->addMessage('error', "Error, la carpeta con ese nombre ya existe");
            return $response->withStatus(302)->withHeader("Location", "/home");


        } else{

            return $response->withStatus(302)->withHeader("Location", "/home");

        }

    }

    public function shareFolder(Request $request, Response $response)
    {
        $error = "";
        $idAdmin = $this->container->get('user_repository')->getID($_SESSION['email']);
        if ($idAdmin == null) $error = true;
        $id_folder = $_POST['id_folder'];
        $email = $_POST['email'];
        $idShared = $this->container->get('user_repository')->getID($email);
        if ($idShared == null) $error = true;
        $rol = $_POST['rol'];

        if ($error ){
            $this->container->get('flash')->addMessage('error', "Error, el usuario con el email '$email' no existe");
            return $response->withStatus(302)->withHeader("Location", "/home");
        } else {
            $exit = $this->container->get('folder_repository')->shareFolder($idAdmin, $idShared, $id_folder, $rol);
            return $response->withStatus(302)->withHeader("Location", "/home");
        }


    }

    public function showSharedFolders(Request $request, Response $response)
    {


        if (isset($_SESSION['email'])) {
            $idUser = $this->container->get('user_repository')->getID($_SESSION['email']);
            $folders = $this->container->get('folder_repository')->selectSharedFolders($idUser);

            $exit = $this->container->get('user_repository')->getActivate($_SESSION['email']);
            //miramos si la cuenta esta activada
            if ($exit == "false") {
                $mensaje = "Activa la cuenta, porfavor";
            } else {
                $mensaje = "";
            }
            $messages = $this->container->get('flash')->getMessages();


            $path = $this->container->get('user_repository')->getProfilePic($_SESSION['email']);
            $username = $this->container->get('user_repository')->getUsername($_SESSION['email']);
            $size = 1024 - ($this->container->get('user_repository')->getSize($_SESSION['email']));
            $sizepercent = ($size / 1024) * 100;


            return $this->container->get('view')->render($response, 'home.html.twig', [
                'username' => $username,
                'folders' => $folders,
                'path' => $path,
                'messages' => $messages,
                'mensaje' => $mensaje,
                'size' => $size,
                'sizepercent' => $sizepercent
            ]);
        } else {
            return $response->withStatus(302)->withHeader("Location", "/login");
        }
    }

    public function enterSharedFolder()
    {
        $_SESSION['shared_folder_id'] = $_POST['id_shared_folder'];

        return $response->withJson(['ok'=>'ok'], 201);
    }

}