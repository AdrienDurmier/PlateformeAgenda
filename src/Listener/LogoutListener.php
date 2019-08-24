<?php

namespace App\Listener;
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
 
class LogoutListener implements LogoutHandlerInterface
{
     /**
     * @{inheritDoc}
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        // Suppression des cookies contenant les tokens d'authentification lors de la déconnexion
        $response->headers->clearCookie('api_user_id');
        $response->headers->clearCookie('api_user_token');
        $response->send();
    }
}