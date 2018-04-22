<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 12/04/2018
 * Time: 18:45
 */

namespace SlimApp\Model;


class User
{

    private $id;
    private $username;
    private $email;
    private $description;
    private $name;
    private $characteristics;
    private $password;
    private $createdAt;
    private $updatedAt;
    private $available_size;
    private $birthdate;
    private $profile_pic;

    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $email
     * @param $description
     * @param $name
     * @param $characteristics
     * @param $password
     * @param $createdAt
     * @param $updatedAt
     * @param $available_size
     * @param $birthdate
     * @param $profile_pic
     */


    public function __construct(
        $id,
        $username,
        $email,
        $description,
        $name,
        $characteristics,
        $password,
        $createdAt,
        $updatedAt,
        $available_size,
        $birthdate,
        $profile_pic
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->description = $description;
        $this->name = $name;
        $this->characteristics = $characteristics;
        $this->password = $password;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->available_size = $available_size;
        $this->birthdate = $birthdate;
        $this->profile_pic = $profile_pic;
    }

    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $email
     * @param $password
     * @param $createdAt
     * @param $updatedAt
     * @param $available_size
     * @param $birthdate
     * @param $profile_pic
     */


    /**
     * @return mixed
     */
    public function getAvailableSize()
    {
        return $this->available_size;
    }

    /**
     * @param mixed $available_size
     */
    public function setAvailableSize($available_size): void
    {
        $this->available_size = $available_size;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param mixed $birthdate
     */
    public function setBirthdate($birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return mixed
     */
    public function getProfilePic()
    {
        return $this->profile_pic;
    }

    /**
     * @param mixed $profile_pic
     */
    public function setProfilePic($profile_pic): void
    {
        $this->profile_pic = $profile_pic;
    }


    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $email
     * @param $password
     * @param $createdAt
     * @param $updatedAt
     */


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
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
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCharacteristics()
    {
        return $this->characteristics;
    }

    /**
     * @param mixed $characteristics
     */
    public function setCharacteristics($characteristics): void
    {
        $this->characteristics = $characteristics;
    }





}