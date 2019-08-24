<?php
namespace App\Notification;

use App\Entity\MessageBug;

class NotificationBug extends Notification {

    public function notify(MessageBug $message) {
        $email = (new \Swift_Message("Plateforme Iptis: Signalement d'un bug"))
            ->setFrom($message->getUser()->getEmail())
            ->setTo('adrien.durmier@amediasolutions.fr')
            ->setBody($this->renderer->render('bug/email.html.twig', [
                'message'   =>  $message
            ]), 'text/html')
        ;
        $this->mailer->send($email);
    }
}