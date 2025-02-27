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
        $sql = "INSERT INTO user(username, email, password, created_at, updated_at, active_account, birthdate ,available_size, nombre, description, characteristics, profile_pic) VALUES(:username, :email, :password, :created_at, :updated_at, :active_account, :birthdate, :available_size, :nombre, :description, :characteristics, :profile_pic)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("password", $user->getPassword(), 'string');
        $stmt->bindValue("created_at", $user->getCreatedAt()->format(self::DATE_FORMAT)); //pasando el Date a String para al BBDD
        $stmt->bindValue("updated_at", $user->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("active_account", "false", 'string');
        $stmt->bindValue("birthdate", $user->getBirthdate(), 'string');
        $stmt->bindValue("available_size", $user->getAvailableSize(), 'float');
        $stmt->bindValue("nombre", $user->getName(), 'string');
        $stmt->bindValue("description", $user->getDescription(), 'string');
        $stmt->bindValue("characteristics", $user->getCharacteristics(), 'string');
        $stmt->bindValue("profile_pic", $user->getProfilePic(), 'string');

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


    public function exist(String $user)
    {
        try {
            $sql = "SELECT * FROM user WHERE :email = email";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("email", $user, 'string');
            $stmt->execute();
            $resul = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resul;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function login(User $user)
    {
        try {
            $sql = "SELECT * FROM user WHERE (:email = email OR :username = username) AND :password = password";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("email", $user->getEmail(), 'string');
            $stmt->bindValue("password", $user->getPassword(), 'string');
            $stmt->bindValue("username", $user->getUsername(), 'string');

            $stmt->execute();
            $result = $stmt->fetchAll();

            return $result;
        } catch (DBALException $e) {
            return false;
        }
    }

    public function updateEmail(String $email, String $new_email)
    {
        $q = $this->database->query("UPDATE user SET email='".$new_email."' WHERE email='".$email."'");
        $result = $q->execute();
        return $result;

    }

    public function updatePassword(String $email, String $password)
    {
        $q = $this->database->query("UPDATE user SET password ='".$password."'WHERE email='".$email."'");
        $result = $q->execute();
        return $result;
    }

    public function updateProfilePicPath(String $email, String $photo)
    {
        $q = $this->database->query("UPDATE user SET profile_pic ='".$photo."'WHERE email='".$email."'");
        $result = $q->execute();
        return $result;
    }



    public function remove(String $email, int $id)
    {

        $sql = "DELETE FROM shareFolder WHERE id_shared = :id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("id", $id);
        $exit = $stmt->execute();

        $sql = "DELETE FROM user WHERE email = :email";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("email", $email, 'string');
        $exit = $stmt->execute();

        return $exit;

    }

    public function activate(String $email)
    {
        $q = $this->database->query("UPDATE user SET active_account = true WHERE email='".$email."'");
        $result = $q->execute();
        return $result;

    }

    public function getProfilePic(String $email){
        $sql = "SELECT profile_pic FROM user WHERE email = :email";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("email", $email, 'string');
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res[0]['profile_pic'];
    }

    public function getUsername(String $email){
        $sql = "SELECT username FROM user WHERE email = :email";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("email", $email, 'string');
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res[0]['username'];
    }

    public function getID(String $email){
        $sql = "SELECT id FROM user WHERE email = :email";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("email", $email, 'string');
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res[0]['id'];
    }

    public function getEmail(String $username){
        $sql = "SELECT email FROM user WHERE username = :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $username, 'string');
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getActivate(String $username){
        $sql = "SELECT * FROM user WHERE username = :username OR email = :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $username, 'string');
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $res[0]['active_account'];
    }

    public function getSize(String $email)
    {
        $q = $this->database->query("SELECT `available_size` FROM `user` WHERE email='".$email."'");
        $f = $q->fetch();
        $result = $f['available_size'];
        return $result;
    }

    public function setSize(String $email, float $size)
    {
        $sql = "UPDATE user SET available_size = :size WHERE email = :email";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("size", $size);
        $stmt->bindValue("email", $email, 'string');
        $stmt->execute();



    }

    public function getEmailFromId(int $id)
    {
        $sql = "SELECT email FROM user WHERE id = :id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("id", $id);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;

    }





}