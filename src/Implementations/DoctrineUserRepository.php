<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 12/04/2018
 * Time: 19:12
 */

namespace SlimApp\Implementations;
use Doctrine\DBAL\Connection;
use SlimApp\Model\User;
use \SlimApp\Model\UserRepository;

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
        $sql = "INSERT INTO user(username, email, password, created_at, updated_at) VALUES(:username, :email, :password, :created_at, :updated_at)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("password", $user->getPassword(), 'string');
        $stmt->bindValue("created_at", $user->getCreatedAt()->format(self::DATE_FORMAT)); //pasando el Date a String para al BBDD
        $stmt->bindValue("updated_at", $user->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();
    }
}