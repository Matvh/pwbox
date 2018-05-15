<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 1/5/18
 * Time: 16:28
 */

namespace SlimApp\Controller;

use Doctrine\DBAL\DBALException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

class MyAccountController
{

    protected $container;

    /**
     * HelloController constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response)
    {

        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
            $exit = $this->container->get('user_repository')->exist($email);

            return $this->container->get('view')->render($response, 'myAccount.html.twig', ['user' => $exit[0]]);
        } else {
            return $response->withStatus(403)->withHeader("Location", "/error");
        }
    }

    public function updateData(Request $request, Response $response)
    {

        if (isset($_SESSION['email'])) {
            $results=[];
            $email = $_SESSION['email'];

            //cambiar mail
            if (isset($_POST['email']) && !empty($_POST['email']) && $_POST['email'] != " ") {

                if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){

                    $new_email = $_POST['email'];
                    $res = $this->container->get('user_repository')->updateEmail($email, $new_email);

                    if($res){
                        $_SESSION['email'] = $new_email;
                        $ok['email'] = true;
                        $results['email'] = "Email successfully updated";
                    }else{
                        $results['email'] = "There was a problem updating your email";
                    }
                }else{
                    $results['email'] = "Incorrect email format";
                }
            }

            //cambiar password
            if (isset($_POST['password']) && !empty($_POST['password']) && $_POST['password'] != " ") {
                $password = $_POST['password'];
                if(strlen($password) >= 6 && strlen($password)<= 12 &&
                    preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password) && preg_match('/[1-9]/', $password)){

                    if($password == $_POST['pass_re']){
                        $password = hash("sha256", $_POST['password']);
                        $result = $this->container->get('user_repository')->updatePassword($_SESSION['email'], $password);
                        if($result){
                            $ok['password'] = true;
                            $results['password'] = 'Password successfully updated';
                        }else{
                            $results['password'] = 'The was a problem updating the password';
                        }
                    }else{
                        $results['password'] = 'The password and the confirm password do not match';
                    }
                }else{
                    $results['password'] = 'The password must have between 6 to 12 characters, at least 1 number and 1 upper case letter';
                }
            }

            //cambiar foto
            if (isset($_FILES["picture"]["name"]) && !empty($_FILES["picture"]["name"]) && $_FILES["picture"]["name"] != '') {

                $email = $_SESSION['email'];
                $oldPath = $this->container->get('user_repository')->getProfilePic($email);
                $oldPic = '/home/vagrant/code/pwbox/public/profilePics/'.$oldPath;

                $u_id = $this->container->get('user_repository')->getID($email);
                $uploadErrors = $this->container->get('upload_photo')->uploadPhoto($u_id);

                if ($uploadErrors == 'success'){
                    $ok['photo'] = true;
                    $results['photo'] = 'Profile picture successfully updated';

                    //remove old picture
                    $newPath = $u_id . '.' . strtolower(pathinfo($_FILES["picture"]["name"],PATHINFO_EXTENSION));

                    //if they have a different ext, then remove the old pic & update database
                    if($oldPath != $newPath){
                        if ($oldPath != 'default.png'){
                            chmod($oldPic, 777);
                            unlink($oldPic);
                        }
                        $this->container->get('user_repository')->updateProfilePicPath($email, $newPath);
                    }
                }else{
                    $results['photo'] = $uploadErrors;
                }
            }

            if (isset($ok['email']) || isset($ok['password']) || isset($ok['photo'])) {
                $newResponse = $response->withJson($results, 201);
                return $newResponse;
            }
            $newResponse = $response->withJson($results, 400);
            return $newResponse;


        } else {
                return $response->withStatus(403)->withHeader("Location", "/error");
        }
    }

    public function deleteUser(Request $request, Response $response)
    {
        $id = $username = $this->container->get('user_repository')->getID($_SESSION['email']);
        $path = $this->container->get('user_repository')->getProfilePic($_SESSION['email']);
        $this->container->get('notification_repository')->deleteNotificationID(intval($id));
        $username = $username = $this->container->get('user_repository')->getUsername($_SESSION['email']);
        $idFolder = $this->container->get('folder_repository')->selectIdRoot("root".$username);
        $this->deleteFolderP($idFolder[0]['id']);

        $this->container->get('user_repository')->remove($_SESSION['email'], $id);
        $dir = '/home/vagrant/code/pwbox/public/uploads/'.$id;
        $this->deleteDirectory($dir);

        if($path != "default.png"){
            unlink('/home/vagrant/code/pwbox/public/profilePics/'.$path);
        }


        session_destroy();
        return $response->withStatus(303)->withHeader("Location", "/login");

    }

    function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
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
}

