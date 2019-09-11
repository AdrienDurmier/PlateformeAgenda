<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManagerInterface;
use Unirest;

class APIJoursFeries
{
    private $params;
    private $em;

    /**
     * APIUsers constructor.
     * @param ParameterBagInterface $params
     * @param EntityManagerInterface $em
     */
    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em)
    {
        $this->params = $params;
        $this->em = $em;
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
        $headers = array('Content-type' => 'application/json');
        $body = Unirest\Request\Body::form(json_encode([]));

        $response_an_prev = Unirest\Request::get($url_api_jours_feries . ($an-1), $headers, $body);
        $response_an = Unirest\Request::get($url_api_jours_feries . $an, $headers, $body);
        $response_an_next = Unirest\Request::get($url_api_jours_feries . ($an+1), $headers, $body);

        $response = array_merge($response_an_prev->body, $response_an->body, $response_an_next->body);

        return $response;
    }


}