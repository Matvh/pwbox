<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 01/05/2018
 * Time: 19:44
 */

namespace SlimApp\Email;


interface EmailSender
{

    public function sendEmail(String $email,String $message, String $subject);

}