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

    // Champions
    public function getChampions()
    {
        $query = '
        {
            champions {
                idChamp
                name
                image
                skins
                spells
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

    // Champion spécifique
    public function getChampion($champion)
    {
        $response = $this->httpClient->request('GET', $this->getParameter('api_url') . "/champions/" . $champion, [
            'verify_peer' => false,
        ]);

        return $response->getContent();
    }

    // Compétences de champion pour idChampion
    public function getChampionSpells($idChamp)
    {
        $query = '
            query GetChampion($idChamp: String!) {
                champion(idChamp: $idChamp) {
                    idChamp
                    name
                    spells
                    passive
                }
        }';

        $response = $this->httpClient->request('POST', $this->getParameter('graphql_url'), [
            'verify_peer' => false,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'query' => $query,
                'variables' => [
                    'idChamp' => $idChamp,
                ],
            ])
        ]);

        return $response->getContent();
    }

    // Sorts d'invocateur
    public function getSortsInvocateur()
    {
        $response = $this->httpClient->request('GET', $this->getParameter('api_url') . "/sort_invocateurs/", [
            'verify_peer' => false,
        ]);

        return $response->getContent();
    }

    // Runes
    public function getRunes()
    {
        $response = $this->httpClient->request('GET', $this->getParameter('api_url') . "/runes", [
            'verify_peer' => false,
        ]);

        return $response->getContent();
    }

    // Rune spécifique
    public function getRune($rune)
    {
        $response = $this->httpClient->request('GET', $this->getParameter('api_url') . "/runes/" . $rune, [
            'verify_peer' => false,
        ]);

        return $response->getContent();
    }

    // Items
    public function getItems()
    {
        $response = $this->httpClient->request('GET', $this->getParameter('api_url') . "/items", [
            'verify_peer' => false,
        ]);

        return $response->getContent();
    }

    // Item spécifique
    public function getItem($item)
    {
        $response = $this->httpClient->request('GET', $this->getParameter('api_url') . "/items/" . $item, [
            'verify_peer' => false,
        ]);

        return $response->getContent();
    }
}
