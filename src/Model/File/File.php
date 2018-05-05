<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 18/4/18
 * Time: 19:33
 */

namespace SlimApp\Model\File;


class File
{
    private $id;
    private $name;
    private $created;
    private $updated;
    private $type;
    private $idFolder;
    private $path;



    /**
     * File constructor.
     * @param $name
     * @param $idFolder
     * @param $created
     */

    public function __construct($name, $idFolder, $created)
    {
        $this->name = $name;
        $this->idFolder = $idFolder;
        $this->created = $created;

    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getIdFolder()
    {
        return $this->idFolder;
    }

    /**
     * @param mixed $idFolder
     */
    public function setIdFolder($idFolder)
    {
        $this->idFolder = $idFolder;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }



}