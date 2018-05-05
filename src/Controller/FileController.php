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


class FileController
{
    protected $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function showFormAction(Request $request, Response $response){
        return $this->container->get('view')
            ->render($response,'file.twig',[]);
    }

    public function uploadFileAction(Request $request, Response $response)
    {
        $directory = '/home/vagrant/code/pwbox//public/uploads/'.$_POST['email']."/";

        $uploadedFiles = $request->getUploadedFiles();
        $moreErrors = null;
        $errors = [];


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
                continue;
            }


            if (!$this->isValidSize($uploadedFile->getSize())){
                $moreErrors['invalidSize'] = true;
                $moreErrors['size'] = 'The maximum available size per file is 2MB';
                $errors[] = sprintf(
                    'Unable to upload the file %s, the size %s is not valid',
                    $fileName,
                    $this->convertToReadableSize($uploadedFile->getSize())
                );
                continue;
            }

            $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $fileName);
        }

        $user['name'] = $this->container->get('user_repository')->getUsername($_POST['email']);
        $user['pic'] = $this->container->get('user_repository')->getProfilePic($_POST['email']);
        $user['email'] = $_POST['email'];

        return $this->container->get('view')
            ->render($response, 'file.twig', ['errors' => $errors, 'isPost' => true, 'moreErrors' => $moreErrors, 'user' => $user]);
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
    private function isValidSize(int $size){
        return $size < 2000000;
    }

    function convertToReadableSize($size){
        $base = log($size) / log(1024);
        $suffix = array("", "KB", "MB", "GB", "TB");
        $f_base = floor($base);
        return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
    }
}