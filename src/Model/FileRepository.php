<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 18/4/18
 * Time: 19:27
 */

namespace SlimApp\Model;


use SlimApp\Model\File\File;

interface FileRepository
{

    public function upload(File $file);

    public function remove(File $file);

    public function download(File $file);



}