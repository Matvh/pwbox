<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 18/4/18
 * Time: 19:32
 */

namespace SlimApp\Model\Folder;


class Folder
{

    private $id;
    private $superRoot;
    private $created;
    private $updated;
    private $name;
    private $path;
    private $isAdmin;

    /**
     * Folder constructor.
     * @param $id
     * @param $created
     * @param $updated
     * @param $name
     */
    public function __construct($id, $created, $updated, $name, $path, $superRoot)
    {
        $this->id = $id;
        $this->created = $created;
        $this->updated = $updated;
        $this->name = $name;
        $this->path = $path;
        $this->superRoot = $superRoot;
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
    public function setId($id)
    {
        $this->id = $id;
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
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getSuperRoot()
    {
        return $this->superRoot;
    }

    /**
     * @param mixed $superRoot
     */
    public function setSuperRoot($superRoot): void
    {
        $this->superRoot = $superRoot;
    }



















}