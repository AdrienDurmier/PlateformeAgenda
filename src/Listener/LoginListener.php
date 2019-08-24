<?php

namespace App\Listener;
 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Entity\User;
use App\Service\APIUsers;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class LoginListener
{
    private $em;
    private $apiUsers;

    public function __construct(EntityManagerInterface $em, APIUsers $apiUsers)
    {
        $this->em = $em;
        $this->apiUsers = $apiUsers;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        // Get the User entity.
        $user = $event->getAuthenticationToken()->getUser();

        // Récupération du nom et prénom de la base d'Heidi
        $user_api = $this->apiUsers->interroger('get', 'users/'.$user->getUsername(), []);
        if(isset($user_api->body) && isset($user_api->body->firstname) && isset($user_api->body->lastname)){
            $user->setFirstname($user_api->body->firstname);
            $user->setLastname($user_api->body->lastname);
            $this->em->persist($user);
            $this->em->flush();
        }

        // Dépot d'un cookie pour permettre la connexion d'une plateforme à une autre
        $cookie_api_user_id = new Cookie('api_user_id', $user->getId());
        $cookie_api_user_token = new Cookie('api_user_token', $user->getToken());
        $response = new Response();
        $response->headers->setCookie($cookie_api_user_id);
        $response->headers->setCookie($cookie_api_user_token);
        $response->send();

        // Persist the data to database.
        $this->em->persist($user);
        $this->em->flush();
    }
}