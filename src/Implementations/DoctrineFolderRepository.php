<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 27/4/18
 * Time: 16:06
 */

namespace SlimApp\Implementations;


use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Connection;
use SlimApp\Model\Folder\Folder;
use SlimApp\Model\FolderRepository;
use SlimApp\Model\User;

class DoctrineFolderRepository implements FolderRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private $database;

    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    public function create(Folder $folder, User $user)
    {
        $sql = "INSERT INTO folder(created_at, updated_at, nombre, path) VALUES(:created_at, :updated_at, :nombre, :path)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("created_at", $folder->getCreated()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $folder->getUpdated()->format(self::DATE_FORMAT));
        $stmt->bindValue("nombre", $folder->getName(), 'string');
        $stmt->bindValue("path", $folder->getPath(), 'string');
        $exit = $stmt->execute();

        $sql = "INSERT INTO notification(info) VALUES(:info)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("info", "Folder created", 'string');
        $exit = $stmt->execute();

        $sql = "INSERT INTO userFolder(id_user, id_folder, id_notification) SELECT user.id, folder.id, notification.id FROM user, notification, folder WHERE
                user.email = :email AND folder.nombre = :nombre AND notification.id = 4";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("nombre", $folder->getName(), 'string');
        $exit = $stmt->execute();



    }

    public function delete(Folder $folder)
    {
        // TODO: Implement delete() method.
    }

    public function update(Folder $folder)
    {
        // TODO: Implement update() method.
    }
}