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
        $sql = "INSERT INTO folder(is_root, created_at, updated_at, name, path) VALUES(:root, :created_at, :updated_at, :nombre, :path)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("root", $folder->getRoot());
        $stmt->bindValue("created_at", $folder->getCreated()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $folder->getUpdated()->format(self::DATE_FORMAT));
        $stmt->bindValue("nombre", $folder->getName(), 'string');
        $stmt->bindValue("path", $folder->getPath(), 'string');
        $stmt->execute();

        $sql = "INSERT INTO notification(info) VALUES(:info)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("info", "Folder created", 'string');
        $stmt->execute();

        $sql = "SELECT * FROM user ";
        $stmt = $this->database->prepare($sql);
        //$stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->execute();
        $email = $stmt->fetchAll();


        $sql = "SELECT id FROM folder WHERE name = :name ";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $folder->getName(), 'string');
        $stmt->execute();
        $exit2 = $stmt->fetchAll();

        $sql = "INSERT INTO userFolder(id_user, id_folder, id_notification) VALUES (:usera, :folder, 4)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("usera", $email[0]['id'], 'string');
        $stmt->bindValue("folder", $exit2[0]['id'], 'string');
        $exit = $stmt->execute();



    }

    public function select(String $user)
    {
        try {
            $sql = "SELECT folder.name FROM folder, userFolder, user WHERE folder.is_root = 1 AND folder.id 
                    = userFolder.id_folder AND userFolder.id_user = user.id AND (user.email = :email OR user.username = :email) ";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("email",$user , 'string');
            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result;
        } catch (DBALException $e) {
            return false;
        }
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