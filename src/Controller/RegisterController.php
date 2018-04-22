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
        $foto = $resul['picture'];


        if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 6 && strlen($password)<= 12
            && $birthday != "" && $username != "" && $description != "" && $characteristics != "" && $name != ""){

            $date = new DateTime('now');
            $user = new User(1, $username, $email, $description,$name, $characteristics, hash("sha256",$password),
                $date, $date, 1.00, $birthday, $foto);
            try {
                $exit = $this->container->get('user_repository')->save($user);
                if($exit) {
                    shell_exec("mkdir /home/vagrant/users/$username");
                    return $this->container->get('view')->render($response, 'login.twig');
                } else {
                    var_dump($resul);
                    var_dump($exit);
                }
            } catch (NotFoundExceptionInterface $e) {
                $e->getTraceAsString();
            } catch (ContainerExceptionInterface $e) {
                $e->getTraceAsString();
            }


        } else {
            echo "ERROR";
            //TODO ha habido un error en la creacion del usuario
        }

    }

}