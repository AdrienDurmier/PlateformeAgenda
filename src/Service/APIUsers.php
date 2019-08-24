<?php

namespace App\Service;
use Doctrine\DBAL\Exception\ServerException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Unirest;

class APIUsers
{
    private $user;
    private $params;
    private $em;
    private $router;

    /**
     * APIUsers constructor.
     * @param Security $security
     * @param ParameterBagInterface $params
     * @param EntityManagerInterface $em
     */
    public function __construct(Security $security, ParameterBagInterface $params, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->user = $security->getUser();
        $this->params = $params;
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * Service permettant de simplifier la communication avec l'API
     *
     * @param string $methode : get, post, put ou delete
     * @param string $uri : fin de l'url absolue
     * @param array $parametres : contenu de la reqûete
     * @return mixed
     * @throws ServerException
     */
    public function interroger($methode = 'get', $uri = '', $parametres = array())
    {
        Unirest\Request::verifyPeer(false); // TODO Disables SSL cert validation temporary
        $url_api_users = $this->params->get('url_api_users');
        $user = $this->user;
        $headers = array('Content-type' => 'application/json', 'Authorization' => 'Bearer '.$user->getToken());
        $body = Unirest\Request\Body::form(json_encode($parametres));
        $response = Unirest\Request::$methode($url_api_users . $uri, $headers, $body);
        switch ($response->code) {
            case 401: // Expired JWT Token
                $headers_refresh_token = array('Content-type' => 'application/json', 'Authorization' => 'Bearer '.$user->getToken());
                $body_refresh_token = Unirest\Request\Body::form(json_encode([
                    'refresh_token' => $user->getRefreshToken()
                ]));
                $response_refresh_token = Unirest\Request::post($url_api_users . 'token/refresh', $headers_refresh_token, $body_refresh_token);
                switch ($response_refresh_token->code) {
                    case 200: 
                        // Mise à jour des nouveaux tokens
                        $user->setToken($response_refresh_token->body->token);
                        $user->setRefreshToken($response_refresh_token->body->refresh_token);
                        $this->em->persist($user);
                        $this->em->flush();
                        // Réexécution de la requête original
                        $body = Unirest\Request\Body::form(json_encode($parametres));
                        $response = Unirest\Request::$methode($url_api_users . $uri, $headers, $body);
                        break;
                }
                break;
        }
        
        return $response;
    }


}