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

            return $this->container->get('view')->render($response, 'myAccount.twig', ['user' => $exit[0]]);
        } else {
            return $response->withStatus(403)->withHeader("Location", "/error");
        }
    }

    public function updateData(Request $request, Response $response)
    {
        //TODO verificaciones
        if (isset($_SESSION['email'])) {

            //cambiar mail
            if (isset($_POST['email']) && !empty($_POST['email']) && $_POST['email'] != " ") {
                var_dump($_SESSION['email']);
                $email = $_SESSION['email'];
                $new_email = $_POST['email'];

                $this->container->get('user_repository')->updateEmail($email, $new_email);
                $this->container->get('user_repository')->updateProfilePicPath($new_email, $new_email.".png");
                rename("/home/vagrant/code/pwbox/public/profilePics/" . $email . ".png","/home/vagrant/code/pwbox/public/profilePics/". $new_email . ".png");
                $_SESSION['email'] = $new_email;
                echo "success";
            } else {
                if (isset($_POST['email']) && (empty($_POST['email']) || $_POST['email'] == " ")) {
                    echo "password must not be empty";
                }
            }

            //cambiar password
            if (isset($_POST['password']) && !empty($_POST['password']) && $_POST['password'] != " ") {
                $password = hash("sha256", $_POST['password']);
                $this->container->get('user_repository')->updatePassword($_SESSION['email'], $password);
                echo "success";
            }


            //cambiar foto
            if (isset($_FILES["picture"]["name"]) && !empty($_FILES["picture"]["name"]) && $_FILES["picture"]["name"] != '') {

                $email = $_SESSION['email'];

                $oldPic = "/home/vagrant/code/pwbox/public/profilePics/".$email.".png";
                chmod($oldPic, 777);
                unlink($oldPic);
                $this->container->get('upload_photo')->uploadPhoto($email);
                echo "success";
                exit();
            }else{
                echo "error";
            }

        } else {
                return $response->withStatus(403)->withHeader("Location", "/error");
        }
    }
}