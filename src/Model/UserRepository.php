<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 12/04/2018
 * Time: 18:52
 */

namespace SlimApp\Model;



interface UserRepository
{
    public function save(User $user);

    public function exist(User $user);

    public function login(User $user);

    public function update(User $user);

    public function remove(String $email);

    public function getSize(String $email);




}