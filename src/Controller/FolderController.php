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

        $date = new DateTime('now');

        $paramValue = $_SESSION['folder_id'];
        $isShared = $this->container->get('folder_repository')->get($paramValue);

        if ($isShared){
            $folder = new Folder(1, $date, $date, $folderName, "path", "false", "true");

        } else {
            $folder = new Folder(1, $date, $date, $folderName, "path", "false", "false");

        }

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

         $idFolder = $_POST['id_folder'];
         $this->deleteFolderP($idFolder);

         return $response->withStatus(302)->withHeader("Location", "/home");


     }

    public function deleteFolderP(int $id)
    {
        $this->container->get('file_repository')->deleteFilesFolder($id);
        $folders = $this->container->get('folder_repository')->selectChild($id);



        foreach ($folders as $folder){
            $this->deleteFolderP($folder['id']);
        }

        $this->container->get('folder_repository')->delete($id);

    }
    public function renameFolder(Request $request, Response $response, array $args)
    {
        $id_folder = $_POST['id_folder'];
        $newName = $_POST['folder_name'];

        $exit = $this->container->get('folder_repository')->rename($newName, $id_folder);

        if($exit)
        {

            return $response->withStatus(302)->withHeader("Location", "/home");


        } else{
            $this->container->get('flash')->addMessage('error', "Error al renombrar la carpeta");
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
        if ($rol == null) $rol = "reader";

        if ($error ){
            $this->container->get('flash')->addMessage('error', "Error, el usuario con el email '$email' no existe");
            return $response->withStatus(302)->withHeader("Location", "/home");
        } else {
            $this->container->get('notification_repository')->add("Se ha compartido una carpeta contigo", $idShared, $id_folder);
            $this->container->get('activate_email')->sendEmail($email, "Se ha compartido una carpeta contigo", "Carpeta compartida - PWBOX");
            $exit = $this->container->get('folder_repository')->shareFolder($idAdmin, $idShared, $id_folder, $rol);
            return $response->withStatus(302)->withHeader("Location", "/home");
        }


    }

    public function showSharedFolders(Request $request, Response $response)
    {


        if (isset($_SESSION['email'])) {
            $files = "";
            $isAdmin = "false";
            $idUser = $this->container->get('user_repository')->getID($_SESSION['email']);
            if(!isset($_SESSION['shared_folder_id']) || $_SESSION['shared_folder_id'] == ""){
                unset($_SESSION['admin']);
                $folders = $this->container->get('folder_repository')->selectSharedFolders2($idUser);
                $parentFolder = null;
            } else {



                $files = $this->container->get('file_repository')->select(intval($_SESSION['shared_folder_id']));

                if($_SESSION['admin'] == "admin"){
                    $isAdmin = "true";
                } else {
                    $isAdmin = "false";
                }

                $folders = $this->container->get('folder_repository')->selectChild($_SESSION['shared_folder_id']);
                $parentFolder = $this->container->get('folder_repository')->selectSharedFolders2($idUser);

            }

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

            $notifications = $this->container->get('notification_repository')->getNotifications($idUser);
            if($parentFolder != null) {
                $hasParent = true;
                return $this->container->get('view')->render($response, 'shared.html.twig', [
                    'username' => $username,
                    'folders' => $folders,
                    'path' => $path,
                    'hasParent' => $hasParent,
                    'parent_folder' => $parentFolder,
                    'messages' => $messages,
                    'mensaje' => $mensaje,
                    'size' => $size,
                    'files' => $files,
                    'isAdmin' => $isAdmin,
                    'sizepercent' => $sizepercent,

                    'notifications' => $notifications]);
            } else {
                $hasParent = false;
                return $this->container->get('view')->render($response, 'shared.html.twig', [
                    'username' => $username,
                    'folders' => $folders,
                    'path' => $path,
                    'hasParent' => $hasParent,
                    'messages' => $messages,
                    'mensaje' => $mensaje,
                    'size' => $size,
                    'files' => $files,
                    'isAdmin' => $isAdmin,
                    'sizepercent' => $sizepercent,

                    'notifications' => $notifications]);
            }
        } else {
            return $response->withStatus(302)->withHeader("Location", "/login");
        }
    }

    public function enterSharedFolder(Request $request, Response $response)
    {
        $_SESSION['shared_folder_id'] = $_POST['id_shared_folder'];

        if(!isset($_SESSION['admin'])){
            $rol = $this->container->get('folder_repository')->getRol($_SESSION['shared_folder_id'])[0]['rol'];
            $_SESSION['admin'] = $rol;
        }





        return $response->withJson(['ok'=>'ok'], 201);
    }

    public function renameSharedFolder(Request $request, Response $response, array $args)
    {
        $id_folder = $_POST['id_folder'];
        $newName = $_POST['folder_name'];

        $exit = $this->container->get('folder_repository')->rename($newName, $id_folder);

        if($exit)
        {

            $this->container->get('flash')->addMessage('error', "Error, la carpeta con ese nombre ya existe");
            return $response->withStatus(302)->withHeader("Location", "/shared");


        } else{

            return $response->withStatus(302)->withHeader("Location", "/shared");

        }

    }

    public function deleteSharedFolder(Request $request, Response $response, array $args)
    {
        $this->firstTime = false;
        $paramValue = $_POST['id_shared_folder'];
        $parent = $this->container->get('folder_repository')->selectParent($paramValue)[0]['id_root_folder'];
        $this->container->get('folder_repository')->delete($paramValue);

        if($parent != null) {
            $id = $args['id'];
            return $response->withStatus(302)->withHeader("Location", "/showSharedFolders");
        } else {
            return $response->withStatus(302)->withHeader("Location", "/showSharedFolders");
        }

    }

    public function createSharedFolder(Request $request, Response $response)
    {

        $folderName = $_POST['folder_name'];


        $paramValue = $_SESSION['shared_folder_id'];



        $date = new DateTime('now');
        $folder = new Folder(1, $date, $date, $folderName, "path", "false", "true");
        $user = $this->container->get('user_repository')->getUsername($_SESSION['email']);
        $email = $_SESSION['email'];
        $user = new User(1, $user, $email, "", "", "", "", $date, $date, 1, "", null);
        $this->container->get('folder_repository')->create($folder, $user);
        $child = $this->container->get('folder_repository')->selectChildId($folderName);
        $this->container->get('folder_repository')->createChild(intval($paramValue), $child[0]['id']);
        return $response->withStatus(302)->withHeader("Location", "/shared");

    }

    public function resetShared(Request $request, Response $response)
    {

    }

}