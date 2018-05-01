<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 01/05/2018
 * Time: 11:22
 */

namespace SlimApp\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;


class ActivateAccountController
{
    protected $container;

    /**
     * ActivateAccountController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function activateAction(Request $request, Response $response){
        $email = $_GET['email'];
        var_dump($email);
        try{
            $this->container->get('user_repository')->activate($email);
            header('Location: http://pwbox.test/home?email='.$email);
        } catch (NotFoundExceptionInterface $e) {
            //TODO mostrar error en twig
            echo 'Message: ' .$e->getMessage();
        } catch (ContainerExceptionInterface $e) {
            //TODO mostrar error en twig
            echo 'Message: ' .$e->getMessage();
        }
    }

}