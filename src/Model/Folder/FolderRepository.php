<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 18/4/18
 * Time: 19:29
 */

namespace SlimApp\Model;


interface FolderRepository
{

    public function create(FolderRepository $folder);

    public function delete(FolderRepository $folder);

    public function update(FolderRepository $folder);

}