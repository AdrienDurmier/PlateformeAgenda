<?php

namespace App\Service;

use Doctrine\DBAL\Exception\ServerException;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Unirest;

class APIJoursFeries
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
     * @param int $an
     * @return mixed
     */
    public function interroger(int $an)
    {
        Unirest\Request::verifyPeer(false); // TODO Disables SSL cert validation temporary
        $url_api_jours_feries = $this->params->get('url_api_jours_feries');
        $user = $this->user;
        $headers = array('Content-type' => 'application/json');
        $body = Unirest\Request\Body::form(json_encode([]));
        $response = Unirest\Request::get($url_api_jours_feries . $an, $headers, $body);
        return $response->body;
    }


}