<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 3/5/18
 * Time: 19:00
 */

namespace SlimApp\Controller;


use DateTime;
use Psr\Container\ContainerInterface;
USE Slim\Http\Request;
use Slim\Http\Response;
use SlimApp\Model\Folder\Folder;
use SlimApp\Model\User;

class FolderController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $paramValue = $args['id'];
        $exit = $this->container->get('folder_repository')->selectChild($paramValue);


        return $this->container->get('view')->render($response, 'home.twig', ['folders' => $exit, 'id_folder' => $paramValue]);
    }

    public function createFolder(Request $request, Response $response, array $args)
    {
        $folderName = $_POST['folder_name'];
        if ($args != null) {
            $paramValue = $args['id'];
            $root = 0;
            $date = new DateTime('now');
            $folder = new Folder(1, $date, $date, $folderName, "path", $root);
            $user = $this->container->get('user_repository')->getUsername($_SESSION['email']);
            $email = $this->container->get('user_repository')->getEmail($_SESSION['email']);




            $user = new User(1, $user, $email, "hola", "miquel", "jeje", "lolo", $date, $date, 1, "02/02/02", null);
            $this->container->get('folder_repository')->create($folder, $user);
            $child = $this->container->get('folder_repository')->selectChildId($folderName);
            $this->container->get('folder_repository')->createChild($paramValue, $child[0]['id']);
        }
        else {
            $root = 1;
            $date = new DateTime('now');
            $folder = new Folder(1, $date, $date, "hola", "path", $root);
            $user = $this->container->get('user_repository')->getUsername($_SESSION['email']);
            $email = $this->container->get('user_repository')->getEmail($_SESSION['email']);




            $user = new User(1, $user, $email, "hola", "miquel", "jeje", "lolo", $date, $date, 1, "02/02/02", null);
            $this->container->get('folder_repository')->create($folder, $user);
            $child = $this->container->get('folder_repository')->selectChildId("hola");
        }


    }

}