<?php

namespace App\Service;

use App\HttpClient\LoLHttpClient;

/**
 * Service responsable de la gestion des opérations liées aux runes
 */
class RuneService
{
    /** 
     * Instance du client HTTP pour League of Legends
     */
    private $lol;

    /**
     * Constructeur qui injecte le LoLHttpClient dans ce service
     *
     * @param LoLHttpClient $lol Le client utilisé pour les requêtes HTTP liées aux données de League of Legends
     */
    public function __construct(LoLHttpClient $lol)
    {
        $this->lol = $lol;
    }

    /**
     * Récupère et renvoie les données complètes des runes
     *
     * @return array Tableau de runes indexé par leurs ID
     */
    public function getRunes()
    {
        $runesData = $this->lol->getRunes();
        $runesData = json_decode($runesData, true)['hydra:member'];


        foreach ($runesData as $rune) {
            $key = $rune['id'];
            $runes[$key] = $rune;
        }
    
        return $runes;
    }

    /**
     * Récupère et renvoie les données d'un arbre de runes spécifique
     *
     * @param string $arbre Arbre (ID) souhaité
     * @return array Données de l'arbre de runes spécifié
     */
    public function getRune($arbre)
    {
        $runeData = $this->lol->getRune($arbre);
        $rune = json_decode($runeData, true);

        return $rune;
    }

    /**
     * Renvoie un tableau prédéfini des valeurs des bonus statistiques pour les runes
     *
     * @return array Tableau des valeurs des bonus statistiques structuré par ligne
     */
    public function getStatistiquesBonus()
    {
        return [
            1 => [
                1 => "+9 force adaptative",
                2 => "+10% vitesse d'attaque",
                3 => "+8 accélération de compétence"
            ],
            2 => [
                4 => "+9 force adaptative",
                5 => "+6 armure",
                6 => "+8 résistance magique"
            ],
            3 => [
                7 => "+15 - 140 PV (selon le niveau)",
                8 => "+6 armure",
                9 => "+8 résistance magique"
            ]
        ];
    }
}
