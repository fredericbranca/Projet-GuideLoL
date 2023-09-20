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

    // Requête api pour récupérer la liste des champions 
    public function getChampions()
    {
        $query = '
        {
            champions {
                idChamp
                name
                image
                skins
            }
        }';

        $response = $this->httpClient->request('POST', $this->getParameter('graphql_url'), [
            'verify_peer' => false,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'query' => $query,
                ])
            ]);
            
        return $response->getContent();
    }

    public function getGame($champion)
    {
        $response = $this->httpClient->request('GET', $this->getParameter('api_url') . "/champions/" . $champion, [
            'verify_peer' => false,
        ]);

        return $response->getContent();
    }
}
