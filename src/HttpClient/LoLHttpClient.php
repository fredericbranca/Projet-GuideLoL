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

    // Requête GraphQL pour récupérer des informations spécifiques sur les champions
    public function getChampions()
    {
        // Création de la requête GraphQL
        // La requête demande les champs idChamp, name, image, skins et spells des champions du jeu
        // GraphQL permet sélectionner spécifiquement les données qu'on veut récupérer 
        // = gain de performance pour le chargement
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

        // Envoi de la requête GraphQL via une requête HTTP POST
        // La requête est envoyée à l'URL
        $response = $this->httpClient->request('POST', $this->getParameter('graphql_url'), [
            // Désactivation de la vérification SSL
            // A mettre sur true en prod car false expose aux attaques man-in-the-middle
            'verify_peer' => false,
            // Encodage de la requête GraphQL en JSON et on l'ajoute dans le corps de la requête HTTP
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            // Encodage de la requête GraphQL en JSON et on l'ajoute dans le corps de la requête HTTP
            'body' => json_encode([
                'query' => $query,
            ])
        ]);

        // Renvoi de la réponse de la requête
        // La fonction retourne le contenu de la réponse (JSON avec les données demandées)
        return $response->getContent();
    }

    // Fonction pour rechercher des champions
    public function searchChampions($search)
    {
        // Création de la requête GraphQL
        // La requête demande les champs idChamp, name, et image des champions dont le nom correspond à $search
        // GraphQL permet sélectionner spécifiquement les données qu'on veut récupérer 
        // = gain de performance pour le chargement
        $query = '
    {
        champions (name: "' . $search . '") {
            idChamp
            name
            image
        }
    }';

        // Envoi de la requête GraphQL via une requête HTTP POST
        // La requête est envoyée à l'URL
        $response = $this->httpClient->request('POST', $this->getParameter('graphql_url'), [
            // Désactivation de la vérification SSL
            // A mettre sur true en prod car false expose aux attaques man-in-the-middle
            'verify_peer' => false,
            // Définition du type JSON
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            // Encodage de la requête GraphQL en JSON et on l'ajoute dans le corps de la requête HTTP
            'body' => json_encode([
                'query' => $query,
            ])
        ]);

        // Renvoi de la réponse de la requête
        // La fonction retourne le contenu de la réponse (JSON avec les données demandées)
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
                {
                    champions (idChamp: "'. $idChamp .'") {
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
                'query' => $query
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
