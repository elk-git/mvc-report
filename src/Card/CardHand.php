<?php

namespace App\Card;

class CardHand
{
    protected array $cards = [];

    public function __construct(array $cards)
    {
        foreach ($cards as $card) {
            if (!$card instanceof Card) {
                throw new \InvalidArgumentException('All elements must be instances of Card.');
            }
        }

        $this->cards = $cards;
    }
}
