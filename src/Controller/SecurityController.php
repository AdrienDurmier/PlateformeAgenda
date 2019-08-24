<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param Security $security
     * @return Response
     */
    public function home(Security $security): Response
    {
        $user = $security->getUser();
        if($user){
            return $this->redirectToRoute('back.index');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }


    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $cookies = $request->cookies;
        if ($cookies->has('api_user_id') && $cookies->has('api_user_token'))
        {
            $cookies->get('api_user_token');
            $user = $this->getDoctrine()->getRepository(User::class)->find($cookies->get('api_user_id'));
            // Si l'utilisateur n'existe plus (cas où il a été supprimé et les cookies sont toujours présent sur le poste local)
            if($user == null){
                // Alors il faut supprimer les cookies et rediriger vers le formulaire de connexion
                $response = new Response();
                $response->headers->clearCookie('api_user_id');
                $response->headers->clearCookie('api_user_token');
                $response->send();
                $this->addFlash('warning', "Vous avez été redirigé car l'identifiant de votre cookie de connexion ne correspond à aucun utilisateur.");
                return $this->redirectToRoute('app_login');
            }
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->container->get('security.token_storage')->setToken($token);         
            $this->container->get('session')->set('_security_main', serialize($token));
            $this->addFlash('success', "Vous avez été connecté automatiquement via votre cookie d'authentification.");
            return $this->redirectToRoute('back.index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }
}
