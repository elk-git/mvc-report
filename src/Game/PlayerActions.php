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
    private bool $endTurn = false;
    private string $endMessage;
    private string $name = "Player";
    private const BLACKJACK_VALUE = 21;

    /**
     * @param PlayerAction  $action antingen "hit" or "stand".
     * @param CardGraphic|null $card
     * @return void
     */
    public function play(PlayerAction $action, ?CardGraphic $card = null): void
    {
        if ($this->endTurn) {
            throw new Exception("Player has ended their turn, cannot play.");
        }
        if ($action === PlayerAction::HIT) {
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
        $this->endTurn = $this->isBusted();
        if ($this->endTurn) {
            $this->endMessage = $this->name . " has busted.";
        }
    }

    /**
     * @return void
     */
    protected function stand(): void
    {
        $this->endMessage = $this->name . " stands.";
        $this->endTurn = true;
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
     * @return string|bool
     */
    public function getEndMessage(): string|bool
    {
        if ($this->endTurn) {
            return $this->endMessage;
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
