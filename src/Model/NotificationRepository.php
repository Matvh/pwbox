<?php
/**
 * Created by PhpStorm.
 * User: Rada
 * Date: 8/5/18
 * Time: 19:52
 */

namespace SlimApp\Model;


use SlimApp\Model\Notification\Notification;

interface NotificationRepository
{

    function addNotification(Notification $notification);

    function deleteNotifications(String $email);

    function getNotifications(String $email);



}