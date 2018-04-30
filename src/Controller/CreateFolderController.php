<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 27/4/18
 * Time: 17:02
 */

namespace SlimApp\Controller;


use DateTime;
use SlimApp\Model\Folder\Folder;
use SlimApp\Model\User;

class CreateFolderController
{

    protected $container;

    /**
     * HelloController constructor.
     * @param $container
     */
    public function __construct($container) {
        $this->container = $container;
    }

    public function __invoke()
    {
        $date = new DateTime('now');
        $folder = new Folder(1, $date, $date, "prueba", "/home/miquel");
        $user = new User(1, "miquel","miquel@gmail.com", "hola", "miquel", "jeje", "lolo", $date, $date, 1, "02/02/02", null);
        $this->container->get('folder_repository')->create($folder, $user);

    }
}