<?php
/**
 * Created by PhpStorm.
 * User: Rada
 * Date: 8/5/18
 * Time: 19:49
 */

namespace SlimApp\Implementations;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use SlimApp\Model\NotificationRepository;


class DoctrineNotificationRepository implements NotificationRepository
{

    private $database;

    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    function deleteNotification(int $id){
        try {
            $sql = "DELETE FROM notification WHERE id = :id";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("id", $id, 'int');
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (DBALException $e) {
            return false;
        }

    }

    function getNotifications(String $email){
        try {
            $sql = "SELECT info, notification.id FROM notification, user WHERE email = :email AND
                    id_user = id.user ORDER BY notification.id DESC;";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("email", $email, 'string');
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (DBALException $e) {
            return false;
        }

    }

    public function add(String $mensaje, int $idUser, int $idFolder)
    {
        $sql = "INSERT INTO notification(info, id_user, id_folder) VALUES(:info, :id_user, :id_folder)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("info", $mensaje);
        $stmt->bindValue("id_user", $idUser);
        $stmt->bindValue("id_folder", $idFolder);
        $stmt->execute();

    }

}