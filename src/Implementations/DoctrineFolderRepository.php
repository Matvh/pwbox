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

        $sql = "INSERT INTO folder(super_root, created_at, updated_at, isShared,name, path) VALUES( :super_root, :created_at, :updated_at,:isAdmin, :nombre, :path)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("super_root", $folder->getSuperRoot());
        $stmt->bindValue("created_at", $folder->getCreated()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $folder->getUpdated()->format(self::DATE_FORMAT));
        $stmt->bindValue("isAdmin", $folder->getisShared());
        $stmt->bindValue("nombre", $folder->getName(), 'string');
        $stmt->bindValue("path", $folder->getPath(), 'string');
        $stmt->execute();


        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->execute();
        $email = $stmt->fetchAll();


        $sql = "SELECT id FROM folder WHERE name = :name ORDER BY id DESC";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $folder->getName(), 'string');
        $stmt->execute();
        $exit2 = $stmt->fetchAll();

        $sql = "INSERT INTO userFolder(id_user, id_folder) VALUES (:usera, :folder)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("usera", $email[0]['id'], 'string');
        $stmt->bindValue("folder", $exit2[0]['id'], 'string');
        $exit = $stmt->execute();


    }

    public function exist(String $name, int $id)
    {
        try {
            $sql = "SELECT folder.name FROM folder, userFolder WHERE folder.name = :name AND userFolder.id_folder = folder.id AND userFolder.id_user = :id";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("name", $name, 'string');
            $stmt->bindValue("id", $id);

            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function select(String $user)
    {
        try {
            $sql = "SELECT folder.name, folder.id FROM folder, userFolder, user WHERE folder.is_root = 1 AND folder.id 
                    = userFolder.id_folder AND userFolder.id_user = user.id AND (user.email = :email OR user.username = :email) ";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("email", $user, 'string');
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function getOwner(int $id)
    {
        try {
            $sql = "SELECT id_user FROM userFolder WHERE id_folder = :folder_id";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("folder_id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function selectIdRoot(String $user)
    {
        try {
            $sql = "SELECT id FROM folder WHERE name = :user ";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("user", $user, 'string');
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function selectChild(int $id)
    {
        try {
            $sql = "SELECT folder.name, folder.id FROM folder, folderFolder WHERE folderFolder.id_root_folder = :id AND folderFolder.id_folder = folder.id";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("id", $id, 'string');
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function selectParent(int $id)
    {

        try {
            $sql = "SELECT id_root_folder FROM folderFolder WHERE id_folder = :id";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result;
        } catch (DBALException $e) {
            return false;
        }

    }

    public function selectParentShared(int $id, int $idFolder)
    {
        try {
            $sql = "SELECT id_root_folder FROM folderFolder, shareFolder, user WHERE folderFolder.id_folder = :id AND 
          shareFolder.id_folder = folderFolder.id_folder AND shareFolder.id_shared = :idUser ";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("id", $id);
            $stmt->bindValue("idUser", $idFolder);

            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result;
        } catch (DBALException $e) {
            return false;
        }

    }

    public function selectChildId(String $name)
    {
        try {
            $sql = "SELECT folder.id FROM folder WHERE folder.name = :name ORDER BY id DESC";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("name", $name, 'string');

            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function selectSuperRoot(String $name)
    {
        try {
            $sql = "SELECT folder.id FROM folder WHERE folder.name = :name ";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("name", $name, 'string');

            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function createChild(int $parent, int $child)
    {

        try {
            $sql = "INSERT INTO folderFolder(id_root_folder, id_folder) VALUES (:parent, :child)";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("parent", $parent, 'string');
            $stmt->bindValue("child", $child, 'string');

            $result = $stmt->execute();


            return $result;
        } catch (DBALException $e) {
            return false;
        }

    }

    public function delete(int $folder)
    {
        $sql = "DELETE FROM shareFolder WHERE id_folder = :email ";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("email", $folder);
        $exit = $stmt->execute();

        $sql = "DELETE FROM folderFolder WHERE folderFolder.id_folder = :email ";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("email", $folder);
        $exit = $stmt->execute();

        $sql = "DELETE FROM userFolder WHERE id_folder = :email ";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("email", $folder);
        $exit = $stmt->execute();

        $sql = "DELETE FROM folder WHERE id = :id ";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("id", $folder);
        $exit = $stmt->execute();


        return $exit;
    }

    public function deleteAllFolders(String $email, int $id)
    {
        $sql = "DELETE FROM folderFolder WHERE folderFolder.id_user = :id ";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("id", $id);
        $stmt->execute();

        $sql = "DELETE FROM folderFolder WHERE folderFolder.id_user = :id ";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("id", $id);
        $stmt->execute();


    }

    public function update(Folder $folder)
    {
        // TODO: Implement update() method.
    }

    public function rename(String $name, int $id)
    {
        $sql = "UPDATE folder SET name = :name WHERE id = :id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $name);
        $stmt->bindValue("id", $id);
        return $stmt->execute();


    }

    public function shareFolder(int $idAdmin, int $idShared, int $idFolder, String $rol)
    {
        try {
            $sql = "INSERT INTO shareFolder(id_owner, id_folder, id_shared, rol) VALUES (:id_owner, :id_folder, :id_shared, :rol)";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("id_owner", $idAdmin);
            $stmt->bindValue("id_folder", $idFolder);
            $stmt->bindValue("id_shared", $idShared);
            $stmt->bindValue("rol", $rol);

            $result = $stmt->execute();


            return $result;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function selectSharedFolders(int $idFolder)
    {
        try {
            $sql = "SELECT folderFolder.id_root_folder FROM folderFolder, shareFolder WHERE shareFolder.id_folder = folderFolder.id_folder AND shareFolder.id_folder = :idFolder";
            $stmt = $this->database->prepare($sql);

            $stmt->bindValue("idFolder", $idFolder);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (DBALException $e) {
            return false;
        }

    }

    public function selectSharedFolders2(int $id)
    {
        try {
            $sql = "SELECT folder.id, folder.name, shareFolder.rol FROM folder, shareFolder 
                    WHERE shareFolder.id_shared = :id  
                    AND folder.id = shareFolder.id_folder ";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (DBALException $e) {
            return false;
        }

    }

    public function getRol(int $id)
    {
        try {
            $sql = "SELECT rol FROM shareFolder WHERE id_folder = :id";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function get(int $id)
    {
        try {
            $sql = "SELECT * FROM shareFolder WHERE id_folder = :id";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (DBALException $e) {
            return false;
        }

    }

    public function getNameFromId(int $id)
    {
        try {
            $sql = "SELECT name FROM folder WHERE id = :id";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result[0]['name'];
        } catch (DBALException $e) {
            return false;
        }

    }
}