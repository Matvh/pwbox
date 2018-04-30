<?php
/**
 * Created by PhpStorm.
 * User: miquelabellan
 * Date: 20/4/18
 * Time: 18:42
 */

namespace SlimApp\Controller;

use DateTime;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use SlimApp\Model\User;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_TransportException;


class RegisterController
{
    protected $container;

    /**
     * HelloController constructor.
     * @param $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response){


        try {
            return $this->container->get('view')->render($response, 'register.twig');
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }

    public function validateData(Request $request, Response $response)
    {

        $resul = $request->getParsedBody();
        $email = $resul['email'];
        $password = $resul['password'];
        $birthday = $resul['birthday'];
        $username = $resul['username'];
        $description = $resul['description'];
        $name = $resul['name'];
        $characteristics = $resul['characteristics'];
        //$foto = $resul['picture'];
        $foto = "/path";


        if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 6 && strlen($password)<= 12
            && $birthday != "" && $username != "" && $description != "" && $characteristics != "" && $name != "" &&
            preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password)){

            $date = new DateTime('now');
            $user = new User(1, $username, $email, $description,$name, $characteristics, hash("sha256",$password),
                $date, $date, 1024, $birthday, $foto);
            try {
                $exit = $this->container->get('user_repository')->save($user);
                if($exit) {
                    shell_exec("mkdir /home/vagrant/users/$email");
                    $this->sendActivateEmail($email);
                    $this->uploadImage();
                    return $this->container->get('view')->render($response, 'home.twig', ['email' => $email]);
                } else {
                    var_dump($resul);
                    var_dump($exit);
                }
            } catch (NotFoundExceptionInterface $e) {
                $e->getTraceAsString();
            } catch (ContainerExceptionInterface $e) {
                $e->getTraceAsString();
            }


        } else {
            echo "ERROR";
            //TODO ha habido un error en la creacion del usuario
        }

    }

    private function uploadImage()
    {
        var_dump($_FILES);
        $target_dir = "/home/vagrant/code/pwbox/public/profilePics/";
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
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

        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
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

    private function sendActivateEmail(String $email)
    {
        try {
            // Create the Transport
            $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465,'ssl'))
                ->setUsername('pwbox18@gmail.com')
                ->setPassword('pwbox1234');

            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($transport);

            // Create a message
            $message = (new Swift_Message('Activate Account'))
                ->setFrom(['pwb@info' => 'pwbox@info'])
                ->setTo([$email])
                ->setBody('Follow the link in order to activate your account');

            // Send the message
            $result = $mailer->send($message);
        }catch (Swift_TransportException $e){
            echo 'Message: ' .$e->getMessage();
        }
    }

}