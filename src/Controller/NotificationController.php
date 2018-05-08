<?php

namespace SlimApp\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use SlimApp\Model\Notification\Notification;

class NotificationController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addNotification(Request $request, Response $response){
        //TODO: Agregarlo a la base de datos
        return $response->withStatus(302)->withHeader("Location", "/home");
    }

    public function deleteNotifications(Request $request, Response $response){
        //TODO: Eliminarlo de la base de datos
        return $response->withStatus(302)->withHeader("Location", "/home");
    }

}