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
            return $this->container->get('view')->render($response, 'register.html.twig');
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
        $foto = 'default.png';


        $existe = $this->container->get('user_repository')->getEmail($username);

        if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 6 && strlen($password)<= 12
            && $birthday != "" && $username != "" && $description != "" && $characteristics != "" && $name != "" &&
            preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password) &&
            preg_match('/[1-9]/', $password) && $existe == null ){

            $date = new DateTime('now');
            $user = new User(1, $username, $email, $description,$name, $characteristics, hash("sha256",$password),
                $date, $date, 1073741824, $birthday, $foto);
            try {
                $exit = $this->container->get('user_repository')->save($user);
                if($exit) {
                    $user_id = $this->container->get('user_repository')->getID($email);
                    shell_exec("mkdir ../public/uploads/$user_id");
                    $this->container->get('folder_repository')->create(new Folder(1, $date,$date, "root".$username,"path", "true" , "false"), $user);
                    $subject = 'Activate Account';
                    $message = 'Follow the link in order to activate your account http://pwbox.test/activate?email='.$email;
                    $this->container->get('activate_email')->sendEmail($email, $message, $subject);

                    if (isset($_FILES["picture"]["name"]) && !empty($_FILES["picture"]["name"]) && $_FILES["picture"]["name"] != '') {
                        $uploadErrors = $this->container->get('upload_photo')->uploadPhoto($user_id);
                        if ($uploadErrors == 'success'){
                            //update path
                            $photo = $user_id . '.' . strtolower(pathinfo($_FILES["picture"]["name"],PATHINFO_EXTENSION));
                            $this->container->get('user_repository')->updateProfilePicPath($email, $photo);
                        }else{
                            $this->container->get('flash')->addMessage('error', $uploadErrors . 'You can change your picture in MyAccount');
                        }
                    }

                    $_SESSION['email'] = $user->getEmail();
                    $data['username'] = $username;
                    $data['user_id'] = $user_id;

                    $foldersRoot = $this->container->get('folder_repository')->selectSuperRoot("root".$username)[0]['id'];
                    $_SESSION['folder_id'] = $foldersRoot;

                    return $response->withStatus(302)->withHeader("Location", "/home", array('data' => $data));

                } else {
                    return $this->container->get('view')->render($response, 'register.html.twig',
                        ['mensaje' => "Hemos tenido un problema en nuestra base de datos, vuelve a intentarlo"]);
                }
            } catch (NotFoundExceptionInterface $e) {
                $e->getTraceAsString();
            } catch (ContainerExceptionInterface $e) {
                $e->getTraceAsString();
            }
        } else {
            return $this->container->get('view')->render($response, 'register.html.twig',
                ['mensaje' => "Error en algun campo"]);
        }
    }
}