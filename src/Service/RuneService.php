<?php

namespace App\Service;

use App\HttpClient\LoLHttpClient;

class RuneService
{
    private $lol;

    public function __construct(LoLHttpClient $lol)
    {
        $this->lol = $lol;
    }

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

    public function getRune($rune)
    {
        $runeData = $this->lol->getRune($rune);
        $rune = json_decode($runeData, true);

        return $rune;
    }
}
