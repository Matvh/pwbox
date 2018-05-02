<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 01/05/2018
 * Time: 19:48
 */

namespace SlimApp\Email;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_TransportException;

class SwiftEmail implements EmailSender
{

    public function sendActivateEmail(String $email)
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
                ->setBody('Follow the link in order to activate your account http://pwbox.test/activate?email='.$email);

            // Send the message
            $result = $mailer->send($message);
        }catch (Swift_TransportException $e){
            echo 'Message: ' .$e->getMessage();
        }
    }
}