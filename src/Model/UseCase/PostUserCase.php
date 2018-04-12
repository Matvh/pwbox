<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 12/04/2018
 * Time: 18:55
 */

namespace SlimApp\Model\UseCase;
use SlimApp\Model\User;
use SlimApp\Model\UserRepository;

//SERVICES

class PostUserCase
{
    /** UserRepo**/
    private $repo;

    /**
     * PostUserCase constructor.
     * @param $repo
     */
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(array $rawData)
    {
        $now = new \DateTime('now');
        $user = new User(null, $rawData['username'],$rawData['email'],$rawData['password'],$now,$now);
        $this->repo->save($user);
    }


}