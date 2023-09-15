<?php

namespace App\HttpClient;

use App\Factory\XmlResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class LoLHttpClient
 * @package App\Client
 */

class LoLHttpClient extends AbstractController
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * LoLHttpClient constructor.
     *
     * @param HttpClientInterface $lol
     */

    public function __construct(HttpClientInterface $lol)
    {
        $this->httpClient = $lol;
    }

    public function getChampions()
    {
        $response = $this->httpClient->request('GET', "/api/champions", [
            'verify_peer' => false,
        ]);

        return $response->getContent();
    }

    public function getGame($champion)
    {
        $response = $this->httpClient->request('GET', "/api/champions/$champion", [
            'verify_peer' => false,
        ]);

        return $response->getContent();
    }
}
