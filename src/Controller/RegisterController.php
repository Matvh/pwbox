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

        $data = $request->getParsedBody();
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $repassword = $data['repassword'];

        //TODO hash password

        if(filter_var($email, FILTER_VALIDATE_EMAIL) && $password == $repassword &&
            strlen($password) >= 6 && strlen($password) <= 12 ){

            $options = [
                'cost' => 11,
            ];
            $date = new DateTime('now');
            //$passwordHash = password_hash($password, PASSWORD_BCRYPT,$options);

            $user = new User(1,'miquel',$email,$password, $date, $date);
            $exit = $this->container->get('user_repository')->save($user);

            if ($exit){
                return $this->container->get('view')->render($response, 'login.twig');
            } else {
                echo "ERROOOR";
                //TODO error
            }


        } else {
            echo "ERROR";
            //TODO ha habido un error en la creacion del usuario
        }

    }

}