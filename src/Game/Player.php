<?php

namespace App\Game;

use App\Game\CardHand;

class Player
{
    /** @var CardHand */
    protected CardHand $hand;

    /**
     * @param CardHand $cardHand
     */
    public function __construct(CardHand $cardHand = null)
    {
        // No type check needed as we are using the Card class.
        $this->hand = $cardHand ?? new CardHand();
    }
}
