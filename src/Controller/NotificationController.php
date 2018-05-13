<?php

namespace SlimApp\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

class NotificationController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function deleteNotification(Request $request, Response $response){

        $id = $_POST['id_notification'];
        $this->container->get('notification_repository')->deleteNotification($id);

    }

}