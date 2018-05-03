<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 18/4/18
 * Time: 19:42
 */

namespace SlimApp\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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
        if (!isset($_SESSION['email'])){
            //TODO landing page explicando de qué va esta cosa
            return $this->container->get('view')->render($response,'login.twig');

        } else {
            $path = $this->container->get('user_repository')->getProfilePic($_SESSION['email']);
            $username = $this->container->get('user_repository')->getUsername($_SESSION['email']);
            $folders = $this->container->get('folder_repository')->select($_SESSION['email']);

            //return $response->withStatus(302)->withHeader("Location", "/login");
            return $this->container->get('view')->render($response,'home.twig', ['email' => $_SESSION['email'],'pic' =>
                $path,'username' => $username, 'folders' => $folders]);
        }
    }
}