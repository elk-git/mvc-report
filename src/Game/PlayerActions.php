<?php

namespace App\Game;

use App\Game\Card;
use App\Game\CardGraphic;
use Exception;

// Typesafty
enum PlayerAction: string
{
    case HIT = 'hit';
    case STAND = 'stand';
}

class PlayerActions extends Player
{
    private const BLACKJACK_VALUE = 21;

    /**
     * @param PlayerAction  $action antingen "hit" or "stand".
     * @param CardGraphic|null $card
     * @return void
     */
    public function play(PlayerAction $action, ?CardGraphic $card = null): void
    {
        if ($action === PlayerAction::HIT && $card !== null) {
            $this->hit($card);
        }

        if ($action === PlayerAction::STAND) {
            $this->stand();
        }
    }

    /**
     * @param Card|CardGraphic $card
     * @return void
     */
    protected function hit(Card|CardGraphic $card): void
    {
        $this->hand->addCard($card);
        return;

    }

    /**
     * @return void
     */
    protected function stand(): void
    {
        return;
    }

    /**
     * @return bool
     */
    public function isBusted(): bool
    {
        if ($this->hand->getTotalValue() > self::BLACKJACK_VALUE) {
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getHandValue(): int
    {
        return $this->hand->getTotalValue();
    }
}
