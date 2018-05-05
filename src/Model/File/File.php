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

    /**
     * File constructor.
     * @param $name
     * @param $idFolder
     * @param $created
     * @param $type
     */


    public function __construct($name, $idFolder, $created, $type)
    {
        $this->name = $name;
        $this->idFolder = $idFolder;
        $this->created = $created;
        $this->updated = $created;
        $this->type = $type;
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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }





}