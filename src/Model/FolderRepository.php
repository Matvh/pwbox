<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 18/4/18
 * Time: 19:29
 */

namespace SlimApp\Model;


use SlimApp\Model\Folder\Folder;

interface FolderRepository
{

    public function create(Folder $folder, User $user);


    public function update(Folder $folder);

}