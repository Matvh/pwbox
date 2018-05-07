<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 18/4/18
 * Time: 19:42
 */

namespace SlimApp\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SlimApp\Model\User;

class LoginController
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
        return $this->container->get('view')->render($response, 'login.twig');


    }

    public function login(Request $request, Response $response)
    {
        $resul = $request->getParsedBody();
        $email = $resul['email'];
        $password = $resul['password'];

        if(!preg_match('/@/', $email)){

            $exit = $this->container->get('user_repository')->getEmail($email);
            if ($exit){
                $email = $exit[0]['email'];
            } else {
                return $this->container->get('view')->render($response, 'login.twig', ['mensaje' => "El usuario no existe"]);

            }
        }

        $date = new DateTime('now');
        $user = new User(1,$email,$email,null, $date, $date, hash("sha256",$password), null, null, null, null, null);
        $exit = $this->container->get('user_repository')->login($user);


        if (empty($exit)){
            return $this->container->get('view')->render($response, 'login.twig', ['mensaje' => "Contraseña erronea"]);

        } else {

            $_SESSION['email'] = $email;
            $path = $this->container->get('user_repository')->getProfilePic($email);
            $username = $this->container->get('user_repository')->getUsername($email);
            $folders = $this->container->get('folder_repository')->select($email);
            $foldersRoot = $this->container->get('folder_repository')->selectSuperRoot("root".$username)[0]['id'];
            $files = $this->container->get('file_repository')->select($foldersRoot);

            if (($exit[0]['email'] == $email || $exit[0]['username'] == $email) && $exit[0]['active_account'] == "true") {
                $paramValue = $response->getHeader('data');
                $exit = $this->container->get('folder_repository')->selectChild($paramValue);
                $files = $this->container->get('file_repository')->select($paramValue);

                return $response->withStatus(302)->withHeader("Location", "/folder/$foldersRoot");

            } else {
                if (($exit[0]['email'] == $email || $exit[0]['username'] == $email) && $exit[0]['active_account'] == "false") {
                    $paramValue = $response->getHeader('data');

                    return $response->withStatus(302)->withHeader("Location", "/folder/$foldersRoot");

                } else {
                    echo "lol";
                }
            }
        }
    }
}