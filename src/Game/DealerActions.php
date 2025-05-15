<?php

namespace App\Game;

use App\Game\Card;
use App\Game\CardGraphic;
use Exception;


class DealerActions extends PlayerActions
{
    private string $name = "Dealer";
    public function play(PlayerAction $action, ?CardGraphic $card = null): void
    {
        // Dealer must hit on 16 or less, stand on 17 or more
        if ($this->getHandValue() <= 16) {
            $this->hit($card);
        } else {
            $this->stand();
        }
    }

}
