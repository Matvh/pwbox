<?php

namespace SlimApp\Model\Notification;

class Notification
{

    private $id;
    private $usermail;
    private $message;


    /**
     * Notification constructor.
     * @param $id
     * @param $userid
     * @param $message
     */
    public function __construct($id, $usermail, $message)
    {
        $this->id = $id;
        $this->usermail = $usermail;
        $this->message = $message;
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
    public function getUsermail()
    {
        return $this->usermail;
    }

    /**
     * @param mixed $usermail
     */
    public function setUsermail($usermail)
    {
        $this->usermail = $usermail;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

}