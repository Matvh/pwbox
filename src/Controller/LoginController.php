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
        /*if (!isset($_SESSION['email'])){
            return $this->container->get('view')->render($response,'login.twig');

        } else {*/
            $info['path'] =  'paths'; //$this->container->get('user_repository')->getProfilePic($_SESSION['email']);
            $info['username'] =  'username'; //$this->container->get('user_repository')->getUsername($_SESSION['email']);
            $info['$folders'] = 'folders'; //$this->container->get('folder_repository')->select($_SESSION['email']);

            //return $response->withStatus(302)->withHeader("Location", "/login");
            //return $this->container->get('view')->render($response,'home.twig', ['email' => $_SESSION['email'],'pic' =>
              //  $path,'username' => $username, 'folders' => $folders]);


            //$url = $app->router->pathFor('/home',['info' => $info]);
            $this->container->get('flash')->addMessage('info', $info);
            return $response->withStatus(302)->withHeader('Location', '/home');

        //}
    }
}