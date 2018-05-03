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

    public function uploadPhoto(String $email)
    {
        $extension = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));
        $foto = $email . '.' . $extension;

        $target_dir = "/home/vagrant/code/pwbox/public/profilePics/";
        $target_file = $target_dir . $foto;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["picture"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["picture"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}