<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 03/05/2018
 * Time: 17:50
 */

namespace SlimApp\Photo;


class Photos
{

    public function uploadPhoto(String $user_id)
    {

        $errors = [];

        $target_dir = "/home/vagrant/code/pwbox/public/profilePics/";
        $target_file = $target_dir . $user_id;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["picture"]["tmp_name"]);
            if ($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                //echo "File is not an image.";
                $uploadOk = 0;
            }
        }


        if(!$this->isValidExtension($imageFileType)){
            $uploadOk = 0;
            $errors["ext"] = true;
            $errors['extMessage'] = sprintf(
                'Valid extensions: jpg, png, jpeg. The file %s, the extension %s is not valid',
                $_FILES["picture"]["name"],
                $imageFileType
            );
        }

        if(!$this->isValidSize($_FILES["picture"]["size"])){
            $uploadOk = 0;
            $errors['size'] = true;
            $errors['sizeMessage'] = sprintf(
                'Valid size: upto 500Kb. The file %s, the size %s is not valid',
                $_FILES["picture"]["name"],
                $this->convertToReadableSize($_FILES["picture"]["size"])
            );
        }

        if ($uploadOk == 0){
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                // echo "The file " . basename($_FILES["picture"]["name"]) . " has been uploaded.";
            }else{
                //error
            }
        }

        return $errors;
    }

    /**
     * @param string $extension
     * @return bool
     */
    private function isValidExtension(string $extension)
    {
        $validExtensions = ['jpg', 'png', 'jpeg'];
        return in_array($extension, $validExtensions);
    }

    /**
     * @param int $size
     * @return bool
     */
    private function isValidSize(int $size){
        return $size < 512000;
    }

    /**
     * @param $size
     * @return string
     */
    function convertToReadableSize($size){
        $base = log($size) / log(1024);
        $suffix = array("", "KB", "MB", "GB", "TB");
        $f_base = floor($base);
        return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
    }
}