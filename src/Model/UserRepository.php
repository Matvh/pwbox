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
}