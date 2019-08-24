<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\APIUsers;

class UserController extends AbstractController
{
    /**
     * @Route("/user/profile", name="user.profil")
     * @param APIUsers $api
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profil(APIUsers $api)
    {
        $user = $this->getUser();

        $tokenParts = explode(".", $user->getToken());  
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        $token_iat = $jwtPayload->iat;
        $token_exp = $jwtPayload->exp;
        
        return $this->render('user/profil.html.twig', [
            'user'      => $user,
            'token_iat' => $token_iat,
            'token_exp' => $token_exp,
        ]);
    }
}
