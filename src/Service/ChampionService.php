<?php

namespace App\Service;

use App\HttpClient\LoLHttpClient;

class ChampionService {
    private $lol;
    private $imgUrl;

    public function __construct(LoLHttpClient $lol, string $imgUrl) {
        $this->lol = $lol;
        $this->imgUrl = $imgUrl;
    }

    public function getChampions() {
        $championsData = $this->lol->getChampions();
        $championsData = json_decode($championsData, true)['data']['champions'];

        foreach ($championsData as $champion) {
            $key = $champion['idChamp'];
            $champions[$key] = $champion;
        }
        return $champions;
    }

    public function getChampion($id) {
        $championData = $this->lol->getChampion($id);
        $championData = json_decode($championData, true);

        return $championData;
    }

    public function getChampionsName() {
        $championsData = $this->lol->getChampions();
        $championsData = json_decode($championsData, true)['data']['champions'];

        foreach ($championsData as $champion) {
            $champions[] = $champion['idChamp'];
        }
        return $champions;
    }

    public function getChampionsIdName() {
        $championsData = $this->lol->getChampions();
        $champions = json_decode($championsData, true)['data']['champions'];

        foreach ($champions as $key => $champion) {
            $championsIdName[$champion['name']] = $champion['idChamp'];
        }
        return $championsIdName;
    }

    public function getChampionImageURL() {
        return $this->imgUrl;
    }

    public function getChampionSpells($idChamp) {
        $championSpellsData = $this->lol->getChampionSpells($idChamp);
        $championSpellsData = json_decode($championSpellsData, true)['data']['champions'];
        
        return $championSpellsData;
    }

    public function searchChampions($search) {
        $searchJSON = $this->lol->searchChampions($search);
        $searchData = json_decode($searchJSON, true)['data']['champions'];

        return $searchData;
    }
}