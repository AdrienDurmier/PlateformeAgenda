<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Unirest;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $params;

    public function __construct(
            EntityManagerInterface $entityManager, 
            UrlGeneratorInterface $urlGenerator, 
            CsrfTokenManagerInterface $csrfTokenManager, 
            UserPasswordEncoderInterface $passwordEncoder, 
            ParameterBagInterface $params
        )
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->params = $params;
    }

    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }
        // Connexion de l'utilisateur à partir de l'API
        $values = array(
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        );

        $url_api_users = $parameterValue = $this->params->get('url_api_users');
        $headers = array('Content-type' => 'application/json');
        $body = Unirest\Request\Body::form(json_encode($values));
        $response = Unirest\Request::post($url_api_users . 'auth/login', $headers, $body);
        
        switch ($response->code) {
            // Authentification OK : si l'utilisateur est reconnu par l'API
            case 200:
                // Récupération des tokens
                $token = $response->body->token;
                $refresh_token = $response->body->refresh_token;
                // Tentative de récupération du user
                $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $credentials['username']]);
                if (!$user) {
                    $user = new User();
                    $user->setUsername($credentials['username']);
                    $user->setEmail($credentials['username']);
                    $user->setPlainPassword($credentials['password']);
                    $user->setEnabled(true);
                }
                // Mise à jour des tokens
                $user->setToken($token);
                $user->setRefreshToken($refresh_token);

                // Affectation des roles depuis la plateforme de gestion des users
                $tokenParts = explode(".", $token);  
                $tokenHeader = base64_decode($tokenParts[0]);
                $tokenPayload = base64_decode($tokenParts[1]);
                $jwtHeader = json_decode($tokenHeader);
                $jwtPayload = json_decode($tokenPayload);
                if(isset($jwtPayload->roles)):
                    foreach($jwtPayload->roles as $role):
                        $user->addRole($role);
                    endforeach;
                endif;

                // Autres événements lors de la connexion dans App\Listener\LoginListener.php
                
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                
                return $user;
                break;
            // Identifiants invalides
            case 401:
                throw new CustomUserMessageAuthenticationException("Identifiants invalides");
                break;
            default:
                // Authentification non autorisée : : si l'utilisateur n'est pas reconnu par l'API
                throw new CustomUserMessageAuthenticationException("L'authentification n'est pas validée par l'API.");
                break;
        } 

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true; //$this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('back.index'));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_login');
    }
}
