<?php

namespace SlimApp\Model\Notification;

class Notification
{

    private $id;
    private $userid;
    private $message;


    /**
     * Notification constructor.
     * @param $id
     * @param $userid
     * @param $message
     */
    public function __construct($id, $userid, $message)
    {
        $this->id = $id;
        $this->userid = $userid;
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
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @param mixed $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
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