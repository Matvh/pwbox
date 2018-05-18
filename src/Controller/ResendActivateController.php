<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 3/5/18
 * Time: 15:58
 */

namespace SlimApp\Controller;



use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ResendActivateController
{

    protected $container;

    /**
     * HelloController constructor.
     * @param $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response)
    {
        $email = $_SESSION['email'];
        $subject = 'Activate Account';
        $message = 'Follow the link in order to activate your account http://pwbox.test/activate?email='.$email;
        $this->container->get('activate_email')->sendEmail($email,$message, $subject);
        $this->container->get('flash')->addMessage('error', "A new mail has been send to '$email'. Check your inbox");
        return $response->withStatus(302)->withHeader("Location", "/home");

    }

}