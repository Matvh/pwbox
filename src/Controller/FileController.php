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
                $errors[] = sprintf(
                    'Unable to upload the file %s, the extension %s is not valid',
                    $fileName,
                    $extension
                );
                continue;
            }
               $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $fileName);
        }

        return $this->container->get('view')
            ->render($response, 'file.twig', ['errors' => $errors, 'isPost' => true]);
    }

    /**
     * Validate the extension of the file
     *
     * @param string $extension
     * @return boolean
     */
    private function isValidExtension(string $extension)
    {
        $validExtensions = ['jpg', 'png', 'pdf'];

        return in_array($extension, $validExtensions);
    }
}