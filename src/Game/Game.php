<?php

namespace App\Game;


use App\Game\PlayerActions;
use App\Game\DealerActions;
use App\Game\DeckOfCards;

class Game
{
    private PlayerActions $player;
    private DealerActions $dealer;
    private DeckOfCards $deck;
    private bool $playerTurn = true;
    private string $endMessage;

    public function __construct(PlayerActions $player = null, DealerActions $dealer = null, DeckOfCards $deck = null)
    {
        $this->player = $player ?? new PlayerActions();
        $this->dealer = $dealer ?? new DealerActions();
        $this->deck = $deck ?? new DeckOfCards();
    }

    public function getPlayer(): PlayerActions
    {
        return $this->player;
    }

    public function getDealer(): DealerActions
    {
        return $this->dealer;
    }

    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }

    public function startGame(): void
    {
        $this->deck->shuffle();

        // Ge ut första kortet till spelaren och dealern.
        $this->player->play(PlayerAction::HIT, $this->deck->drawCard());
        $this->dealer->play(PlayerAction::HIT, $this->deck->drawCard());

        // Andra kortet ut.
        $this->player->play(PlayerAction::HIT, $this->deck->drawCard());
        $this->dealer->play(PlayerAction::HIT, $this->deck->drawCard());

        $this->hasGameEnded();
    }

    public function hasGameEnded(): bool
    {
        // Kolla om spelare har blackjack
        if ($this->player->getHand()->hasBlackJack()) {
            // Har båda blackjack??
            if ($this->dealer->getHand()->hasBlackJack()) {
                $this->playerTurn = false;
                $this->setEndMessage("Båda har blackjack, lika!");
                return true;
            } else {
                $this->playerTurn = false;
                $this->setEndMessage("Spelare har blackjack!");
                return true;
            }
        }
        // Kolla om dealern har blackjack ensam
        if ($this->dealer->getHand()->hasBlackJack()) {
            $this->playerTurn = false;
            $this->setEndMessage("Dealern har blackjack!");
            return true;
        }
        return false;
    }

    public function setEndMessage(string $message): void
    {
        $this->endMessage = $message;
    }

    public function getEndMessage(): string|bool
    {
        return $this->endMessage ?? false;
    }

    public function playerHit(): void
    {
        $this->player->play(PlayerAction::HIT, $this->deck->drawCard());
    }

    public function playerStand(): void
    {
        $this->player->play(PlayerAction::STAND);
        $this->playerTurn = false;
    }

    public function checkPlayerTurn(): void
    {
        if ($this->player->isBusted()) {
            $this->playerTurn = false;

        }
    }

    public function getPlayerMessage(): bool|string
    {
        return $this->player->getEndMessage();
    }

    public function getDealerMessage(): bool|string
    {
        return $this->dealer->getEndMessage();
    }

    public function dealerPlay(): void
    {
        $this->dealer->play();
    }
}
