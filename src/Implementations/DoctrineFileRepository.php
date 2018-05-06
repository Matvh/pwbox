<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 05/05/2018
 * Time: 17:15
 */

namespace SlimApp\Implementations;


use SlimApp\Model\File\File;
use SlimApp\Model\FileRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Connection;

class DoctrineFileRepository implements FileRepository
{

    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private $database;

    /**
     * DoctrineFileRepository constructor.
     * @param $database
     */
    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    public function upload(File $file)
    {
        $sql = "INSERT INTO file(created_at, updated_at, name, type, folder_id) VALUES(:created, :updated, :name, :type, :folder)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("created", $file->getCreated()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated", $file->getUpdated()->format(self::DATE_FORMAT));
        $stmt->bindValue("name", $file->getName(), 'string');
        $stmt->bindValue("type", $file->getType(), 'string');
        $stmt->bindValue("folder", $file->getIdFolder());


        try {
            $exit = $stmt->execute();
            if ($exit){
                return true;
            }
            return false;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function remove(File $file)
    {
        // TODO: Implement remove() method.
    }

    public function download(File $file)
    {
        // TODO: Implement download() method.
    }

    public function select(int $id)
    {
        try {
            $sql = "SELECT * FROM file WHERE :id = folder_id";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result;
        } catch (DBALException $e) {
            return false;
        }




    }


}