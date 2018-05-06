<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 20/4/18
 * Time: 18:42
 */

namespace SlimApp\Controller;

use DateTime;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use SlimApp\Model\Folder\Folder;
use SlimApp\Model\User;

class RegisterController
{
    protected $container;

    /**
     * HelloController constructor.
     * @param $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response){


        try {
            return $this->container->get('view')->render($response, 'register.twig');
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }

    public function validateData(Request $request, Response $response)
    {

        $resul = $request->getParsedBody();
        $email = $resul['email'];
        $password = $resul['password'];
        $birthday = $resul['birthday'];
        $username = $resul['username'];
        $description = $resul['description'];
        $name = $resul['name'];
        $characteristics = $resul['characteristics'];
        if (isset($_FILES["picture"]["name"]) && !empty($_FILES["picture"]["name"]) && $_FILES["picture"]["name"] != ''){
            $extension = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));
            $foto = $email.'.'.$extension;
        }else{

            $foto = 'default.png';
        }

        $existe = $this->container->get('user_repository')->getEmail($username);

        if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 6 && strlen($password)<= 12
            && $birthday != "" && $username != "" && $description != "" && $characteristics != "" && $name != "" &&
            preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password) && preg_match('/[1-9]/', $password) && $existe == null){

            $date = new DateTime('now');
            $user = new User(1, $username, $email, $description,$name, $characteristics, hash("sha256",$password),
                $date, $date, 1024, $birthday, $foto);
            try {
                $exit = $this->container->get('user_repository')->save($user);
                if($exit) {
                    shell_exec("mkdir ../public/uploads/$email");
                    $this->container->get('folder_repository')->create(new Folder(1, $date,$date, "root".$username,"path", "false", "true" ), $user);

                    $this->container->get('activate_email')->sendActivateEmail($email);
                    $this->container->get('upload_photo')->uploadPhoto($email);
                    $_SESSION['email'] = $user->getEmail();

                    return $response->withStatus(307)->withHeader("Location", "/folder/$foldersRoot");

                } else {
                    echo "Ha habido un problema con la base de datos";
                }
            } catch (NotFoundExceptionInterface $e) {
                $e->getTraceAsString();
            } catch (ContainerExceptionInterface $e) {
                $e->getTraceAsString();
            }
        } else {
            echo "Error en algun campo!";
        }
    }
}