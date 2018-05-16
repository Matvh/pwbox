<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 26/04/2018
 * Time: 18:54
 */

namespace SlimApp\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use SlimApp\Model\File\File;


class FileController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function showFormAction(Request $request, Response $response)
    {
        //TODO ver la sesion o redireccionar a login
        return $this->container->get('view')
            ->render($response, 'file.html.twig', []);
    }

    public function uploadFileAction(Request $request, Response $response)
    {
        $errors = [];
        $moreErrors = [];
        $user['name'] = $this->container->get('user_repository')->getUsername($_SESSION['email']);
        $user['pic'] = $this->container->get('user_repository')->getProfilePic($_SESSION['email']);
        $user['email'] = $_SESSION['email'];
        $idUser = $this->container->get('user_repository')->getID($_SESSION['email']);

        $directory = '/home/vagrant/code/pwbox//public/uploads/' . $idUser . "/";
        $uploadedFiles = $request->getUploadedFiles();


        if (!empty($uploadedFiles['files'][0]->file)) {

            foreach ($uploadedFiles['files'] as $uploadedFile) {
                if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                    $errors[] = sprintf(

                        'An unexpected error ocurred uploading the file %s',
                        $uploadedFile->getClientFilename()
                    );
                    continue;
                }

                $fileName = $uploadedFile->getClientFilename();
                $fileInfo = pathinfo($fileName);

                $extension = $fileInfo['extension'];

                if (!$this->isValidExtension($extension)) {
                    $moreErrors['invalidExt'] = true;
                    $moreErrors['extensions'] = 'Valid extensions: jpg, png, gif, pdf, md, txt';
                    $errors[] = sprintf(
                        'Unable to upload the file %s, the extension %s is not valid',
                        $fileName,
                        $extension
                    );
                    $this->container->get('flash')->addMessage('error', "No se permite esa extensión: " . $fileName);

                    continue;
                }

                if (!$this->isValidSize($uploadedFile->getSize())) {
                    $moreErrors['invalidSize'] = true;
                    $moreErrors['size'] = 'The maximum available size per file is 2MB';
                    $errors[] = sprintf(
                        'Unable to upload the file %s, the size %s is not valid',
                        $fileName,
                        $this->convertToReadableSize($uploadedFile->getSize())
                    );


                    $this->container->get('flash')->addMessage('error', "Archivo demasiado grande: " . $fileName);


                    continue;
                }

                //Ver si hay espacio para subir los achivos que faltan
                $fileSize = (float) $uploadedFile->getSize() / 1048576; //bytes to megabytes

                $currentSize = $this->container->get('user_repository')->getSize($_SESSION['email']);


                if (($currentSize - $fileSize) <= 0) {
                    $this->container->get('flash')->addMessage('error', "No tienes más capacidad disponible");
                    $moreErrors['maxAvailable'] = true;
                    break;

                } else {
                    $this->container->get('user_repository')->setSize($user['email'],
                        ($currentSize - $fileSize));

                    $file = new File($fileName, $_SESSION['folder_id'], new \DateTime('now'), $extension);
                    $this->container->get('file_repository')->upload($file);
                    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $fileName);
                }
            }

            if (!isset($moreErrors['invalidSize']) && !isset($moreErrors['invalidExt']) && !isset($moreErrors['maxAvailable'])) {
                //$newResponse = $response->withStatus(201);
                $data = array('size' => $fileSize, 'size2' => $uploadedFile->getSize());
                $newResponse = $response->withJson($data, 201);
                return $newResponse;
            }

            //$newResponse = $response->withJson($errors, 400);
            //return $newResponse;
            $data = array('size' => $fileSize, 'size2' => $uploadedFile->getSize());
            $newResponse = $response->withJson($data, 400);
            return $newResponse;
        }
    }


    public function downloadFileAction(Request $request, Response $response){
        $id = $_POST['id_file'];

        $name = $this->container->get('file_repository')->selectFileName($id);
        $idUser = $username = $this->container->get('user_repository')->getID($_SESSION['email']);
        $fichero = '/home/vagrant/code/pwbox/public/uploads/'.$idUser.'/'.$name;

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($fichero).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fichero));
            readfile($fichero);
    }

    /**
     * Validate the extension of the file
     *
     * @param string $extension
     * @return boolean
     */
    private function isValidExtension(string $extension)
    {
        $validExtensions = ['jpg', 'png', 'gif', 'pdf', 'md', 'txt'];
        return in_array($extension, $validExtensions);
    }

    /**
     * @param int $size
     * @return bool
     */
    private function isValidSize(int $size)
    {
        return $size < 2097152;
    }

    /**
     * @param $size
     * @return string
     */
    function convertToReadableSize($size)
    {
        $base = log($size) / log(1024);
        $suffix = array("", "KB", "MB", "GB", "TB");
        $f_base = floor($base);
        return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
    }

    public function deleteFile(Request $request, Response $response)
    {

        $id = $_POST['id_file'];
        $name = $this->container->get('file_repository')->selectFileName($id);
        $idUser = $username = $this->container->get('user_repository')->getID($_SESSION['email']);

        $size = round(filesize('/home/vagrant/code/pwbox/public/uploads/'.$idUser.'/'.$name)/1000000, PHP_ROUND_HALF_EVEN);
        $userSize = $this->container->get('user_repository')->getSize($_SESSION['email']);

        $this->container->get('user_repository')->setSize($_SESSION['email'],$userSize+$size);


        unlink('/home/vagrant/code/pwbox/public/uploads/'.$idUser.'/'.$name);
        $name = $this->container->get('file_repository')->deleteFile($id);
        return $response->withStatus(302)->withHeader("Location", "/home");

    }

    public function renameFile(Request $request, Response $response)
    {
        $id = $_POST['id_file'];
        $newName = $_POST['file_name'];

        $name = $this->container->get('file_repository')->selectFileName($id);
        $idUser = $username = $this->container->get('user_repository')->getID($_SESSION['email']);

        $old = '/home/vagrant/code/pwbox/public/uploads/'.$idUser.'/'.$name;
        $new = '/home/vagrant/code/pwbox/public/uploads/'.$idUser.'/'.$newName;

        rename($old, $new);
        $this->container->get('file_repository')->renameFile($id, $newName);

        return $response->withStatus(302)->withHeader("Location", "/home");


    }

    public function uploadFileSharedAction(Request $request, Response $response)
    {
        $errors = [];
        $moreErrors = [];
        //$user['name'] = $this->container->get('user_repository')->getUsername($_SESSION['email']);
        //$user['pic'] = $this->container->get('user_repository')->getProfilePic($_SESSION['email']);
        //$user['email'] = $_SESSION['email'];
        $idUser = $this->container->get('folder_repository')->getOwner($_SESSION['shared_folder_id'])[0]['id_user'];

        $directory = '/home/vagrant/code/pwbox//public/uploads/' . $idUser . '/';
        $uploadedFiles = $request->getUploadedFiles();


        if (!empty($uploadedFiles['files'][0]->file)) {

            foreach ($uploadedFiles['files'] as $uploadedFile) {
                if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                    $errors[] = sprintf(

                        'An unexpected error ocurred uploading the file %s',
                        $uploadedFile->getClientFilename()
                    );
                    continue;
                }

                $fileName = $uploadedFile->getClientFilename();
                $fileInfo = pathinfo($fileName);

                $extension = $fileInfo['extension'];

                if (!$this->isValidExtension($extension)) {
                    $moreErrors['invalidExt'] = true;
                    $moreErrors['extensions'] = 'Valid extensions: jpg, png, gif, pdf, md, txt';
                    $errors[] = sprintf(
                        'Unable to upload the file %s, the extension %s is not valid',
                        $fileName,
                        $extension
                    );
                    $this->container->get('flash')->addMessage('error', "No se permite esa extensión: " . $fileName);

                    continue;
                }

                if (!$this->isValidSize($uploadedFile->getSize())) {
                    $moreErrors['invalidSize'] = true;
                    $moreErrors['size'] = 'The maximum available size per file is 2MB';
                    $errors[] = sprintf(
                        'Unable to upload the file %s, the size %s is not valid',
                        $fileName,
                        $this->convertToReadableSize($uploadedFile->getSize())
                    );


                    $this->container->get('flash')->addMessage('error', "Archivo demasiado grande: " . $fileName);


                    continue;
                }

                //Ver si hay espacio para subir los achivos que faltan
                $fileSize = $uploadedFile->getSize() / 1024;

                $currentSize = $this->container->get('user_repository')->getSize($_SESSION['email']);


                if ($currentSize - ($fileSize / 1024) <= 0) {
                    $this->container->get('flash')->addMessage('error', "No tienes más capacidad disponible");
                    $moreErrors['maxAvailable'] = true;
                    break;

                } else {
                    $this->container->get('user_repository')->setSize($idUser[0]['email'],
                        $currentSize - ($fileSize / 1024));

                    $file = new File($fileName, $_SESSION['shared_folder_id'], new \DateTime('now'), $extension);
                    $this->container->get('file_repository')->upload($file);
                    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $fileName);
                }
            }

            if (!isset($moreErrors['invalidSize']) && !isset($moreErrors['invalidExt']) && !isset($moreErrors['maxAvailable'])) {
                //$newResponse = $response->withStatus(201);
                $usuario = $_SESSION['email'];
                $paramValue = $_SESSION['shared_folder_id'];
                $idOwner = $this->container->get('folder_repository')->getOwner($paramValue);
                $emailOwner = $this->container->get('user_repository')->getEmailFromId($idOwner[0]['id_user']);

                $folderName = $this->container->get('folder_repository')->getNameFromId(intval($paramValue));
                $this->container->get('notification_repository')->add("El usuario '$usuario' ha subido el archivo '$fileName' en '$folderName'", $idOwner[0]['id_user'], $paramValue);
                $this->container->get('activate_email')->sendEmail($emailOwner[0]['email'], "El usuario '$usuario' ha subido el archivo '$fileName' en '$folderName", "Archivo subido - PWBOX");




                $data = array('size' => $fileSize, 'size2' => $uploadedFile->getSize());
                $newResponse = $response->withJson($data, 201);
                return $newResponse;
            }

            //$newResponse = $response->withJson($errors, 400);
            //return $newResponse;
            $data = array('size' => $fileSize, 'size2' => $uploadedFile->getSize());
            $newResponse = $response->withJson($data, 400);
            return $newResponse;
        }
    }

    public function deleteSharedFile(Request $request, Response $response)
    {

        $id = $_POST['id_file'];
        $name = $this->container->get('file_repository')->selectFileName($id);
        $idUser = $username = $this->container->get('user_repository')->getID($_SESSION['email']);

        $size = round(filesize('/home/vagrant/code/pwbox/public/uploads/'.$idUser.'/'.$name)/1000000, PHP_ROUND_HALF_EVEN);
        $userSize = $this->container->get('user_repository')->getSize($_SESSION['email']);

        $this->container->get('user_repository')->setSize($_SESSION['email'],$userSize+$size);

        $usuario = $_SESSION['email'];
        $paramValue = $_SESSION['shared_folder_id'];
        $idOwner = $this->container->get('folder_repository')->getOwner($paramValue);
        $emailOwner = $this->container->get('user_repository')->getEmailFromId($idOwner[0]['id_user']);

        $folderName = $this->container->get('folder_repository')->getNameFromId(intval($paramValue));
        $this->container->get('notification_repository')->add("El usuario '$usuario' ha eliminado el archivo $name en  '$folderName'", $idOwner[0]['id_user'], $paramValue);
        $this->container->get('activate_email')->sendEmail($emailOwner[0]['email'], "El usuario '$usuario' ha eliminado el archivo $name en  '$folderName'", "Archivo eliminado - PWBOX");


        unlink('/home/vagrant/code/pwbox/public/uploads/'.$idOwner.'/'.$name);
        $name = $this->container->get('file_repository')->deleteFile($id);
        return $response->withStatus(302)->withHeader("Location", "/shared");

    }

    public function renameSharedFile(Request $request, Response $response)
    {
        $id = $_POST['id_file'];
        $newName = $_POST['file_name'];
        $paramValue = $_SESSION['shared_folder_id'];

        $name = $this->container->get('file_repository')->selectFileName($id);
        $idUser = $username = $this->container->get('user_repository')->getID($_SESSION['email']);
        $idOwner = $this->container->get('folder_repository')->getOwner($paramValue);


        $old = '/home/vagrant/code/pwbox/public/uploads/'.$idOwner.'/'.$name;
        $new = '/home/vagrant/code/pwbox/public/uploads/'.$idOwner.'/'.$newName;

        $usuario = $_SESSION['email'];
        $paramValue = $_SESSION['shared_folder_id'];
        $emailOwner = $this->container->get('user_repository')->getEmailFromId($idOwner[0]['id_user']);

        $folderName = $this->container->get('folder_repository')->getNameFromId(intval($paramValue));
        $this->container->get('notification_repository')->add("El usuario '$usuario' ha renombrado el archivo $name con el nombre $newName en  '$folderName'", $idOwner[0]['id_user'], $paramValue);
        $this->container->get('activate_email')->sendEmail($emailOwner[0]['email'], "El usuario '$usuario' ha renombrado el archivo $name con el nombre $newName en '$folderName'", "Archivo renombrado - PWBOX");



        rename($old, $new);
        $this->container->get('file_repository')->renameFile($id, $newName);

        return $response->withStatus(302)->withHeader("Location", "/shared");


    }

}