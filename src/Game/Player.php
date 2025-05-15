<?php

namespace App\Game;

class Player
{
    /** @var array<Card> */
    protected array $cards = [];

    /**
     * @param array<Card> $cards
     */
    public function __construct(CardHand $cardHand = null)
    {
        // No type check needed as we are using the Card class.
        $this->hand = $cardHand ?? new CardHand();
    }
}
