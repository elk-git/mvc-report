<?php

namespace App\Card;

class CardHand
{
    /** @var array<Card> */
    protected array $cards = [];

    /**
     * @param array<Card> $cards
     */
    public function __construct(array $cards)
    {
        // No type check needed as we are using the Card class.
        $this->cards = $cards;
    }
}
