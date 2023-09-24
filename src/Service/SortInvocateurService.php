<?php

namespace App\Service;

use App\HttpClient\LoLHttpClient;

class SortInvocateurService
{
    private $lol;

    public function __construct(LoLHttpClient $lol)
    {
        $this->lol = $lol;
    }

    public function getSortsInvocateur()
    {
        $sortsData = $this->lol->getSortsInvocateur();
        $sortsData = json_decode($sortsData, true)['hydra:member'];

        foreach ($sortsData as $sort) {
            if (in_array("ARAM", $sort['modes']) || in_array("CLASSIC", $sort['modes'])) {
                $sorts[$sort['id']] = $sort;
            }
        }

        return $sorts;
    }

    public function getSortsInvocateurId()
    {
        $sortsData = $this->lol->getSortsInvocateur();
        $sortsData = json_decode($sortsData, true)['hydra:member'];

        foreach ($sortsData as $sort) {
            if (in_array("ARAM", $sort['modes']) || in_array("CLASSIC", $sort['modes'])) {
                $sorts[] = $sort['id'];
            }
        }

        return $sorts;
    }
}
