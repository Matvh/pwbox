<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 12/04/2018
 * Time: 19:12
 */

namespace SlimApp\Implementations;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use PDO;
use SlimApp\Model\User;
use SlimApp\Model\UserRepository;

class DoctrineUserRepository implements  UserRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private $database;

    /**
     * DoctrineUserRepository constructor.
     * @param $database
     */
    public function __construct(Connection $database)
    {
        $this->database = $database;
    }


    public function save(User $user)
    {
        $sql = "INSERT INTO user(username, email, password, created_at, updated_at, active_account, birthdate ,available_size, nombre, description, characteristics) VALUES(:username, :email, :password, :created_at, :updated_at, :active_account, :birthdate, :available_size, :nombre, :description, :characteristics)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("password", $user->getPassword(), 'string');
        $stmt->bindValue("created_at", $user->getCreatedAt()->format(self::DATE_FORMAT)); //pasando el Date a String para al BBDD
        $stmt->bindValue("updated_at", $user->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("active_account", "true", 'string');
        $stmt->bindValue("birthdate", $user->getBirthdate(), 'string');
        $stmt->bindValue("available_size", $user->getAvailableSize(), 'float');
        $stmt->bindValue("nombre", $user->getName(), 'string');
        $stmt->bindValue("description", $user->getDescription(), 'string');
        $stmt->bindValue("characteristics", $user->getCharacteristics(), 'string');
        var_dump($stmt);


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


    public function exist(User $user)
    {
        try {
            $sql = "SELECT username, password FROM user WHERE :username = username";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("username", $user->getUsername(), 'string');
            $stmt->bindValue("password", $user->getPassword(), 'string');
            $stmt->execute();
            $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resul != null;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function login(User $user)
    {
        try {
            $sql = "SELECT username, password FROM user WHERE :email = email AND :password = password";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("email", $user->getEmail(), 'string');
            $stmt->bindValue("password", $user->getPassword(), 'string');
            $stmt->execute();
            $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $resul != null;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function update(User $user)
    {
        // TODO: Implement update() method.
    }
}