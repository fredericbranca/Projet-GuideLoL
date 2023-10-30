<?php

namespace App\Service;

use App\HttpClient\LoLHttpClient;

class ItemService {
    private $lol;

    public function __construct(LoLHttpClient $lol) {
        $this->lol = $lol;
    }

    public function getItems() {
        $itemsData = $this->lol->getItems();
        $itemsData = json_decode($itemsData, true)['hydra:member'];

        foreach ($itemsData as $item) {
            $key = $item['id'];
            $items[$key] = $item;
        }

        return $items;
    }

    public function getItem($item) {
        $itemData = $this->lol->getItem($item);
        $itemData = json_decode($itemData, true);

        return $itemData;
    }
}