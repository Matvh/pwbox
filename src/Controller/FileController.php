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

    public function showFormAction(Request $request, Response $response){
        //TODO ver la sesion o redireccionar a login
        return $this->container->get('view')
            ->render($response,'file.html.twig',[]);
    }

    public function uploadFileAction(Request $request, Response $response)
    {
        $errors = [];
        $moreErrors = [];
        $user['name'] = $this->container->get('user_repository')->getUsername($_SESSION['email']);
        $user['pic'] = $this->container->get('user_repository')->getProfilePic($_SESSION['email']);
        $user['email'] = $_SESSION['email'];

        $directory = '/home/vagrant/code/pwbox//public/uploads/' . $_POST['email'] . "/";
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
                    continue;
                }

                $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $fileName);


                $fileSize = $uploadedFile->getSize() / 1024;
                $currentSize = $this->container->get('user_repository')->getSize($_SESSION['email']);
                $this->container->get('user_repository')->setSize($user['email'], $currentSize - ($fileSize / 1024));

                $file = new File($fileName, $_SESSION['folder_id'], new \DateTime('now'), $extension);
                $this->container->get('file_repository')->upload($file);

                

                return $response->withStatus(302)->withHeader("Location", "/home");
            }
        }
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
        return $size < 262144;
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