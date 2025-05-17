<?php

namespace App\Game;

use App\Game\PlayerActions;
use App\Game\DealerActions;
use App\Game\DeckOfCards;
use App\Game\CardGraphic;

enum GameStates
{
    // BEFORE FLOP - Innan spelet börjar.
    case NotStarted;      // Initial state, game hasn't begun
    case PlayerTurn;      // Players to hit or stand
    case DealerTurn;      // Dealer to play

    // AFTER FLOP - Endast "blackjack" om man får de genom två första korten.
    case PlayerBlackjack; // Player got blackjack
    case DealerBlackjack; // Dealer got blackjack
    case BlackjackPush;   // Both has blackjack.

    // EFTER SPEL - Efter att spelare och dealer har "standat".
    case Push;            // Game is push.
    case PlayerBusted;    // Player went over 21
    case DealerBusted;    // Dealer went over 21
    case PlayerWin;       // Player won
    case DealerWin;       // Dealer won
}

enum GameMessages: string
{
    case NotStarted = "Spelet inte startat.";
    case PlayerTurn = "Spelarens tur.";
    case DealerTurn = "Dealerns tur.";
    case Push = "Resultat: Oavgjort!";
    case PlayerBusted = "Resultat: Spelare förlorare, blev tjock!";
    case DealerBusted = "Resultat: Dealern förlorarde, blev tjock!";
    case PlayerWin = "Resultat: Spelare vann med högre värde!";
    case DealerWin = "Resultat: Dealern vann med högre värde!";
    case PlayerBlackjack = "Resultat: Spelare vann, har blackjack!";
    case DealerBlackjack = "Resultat: Dealern vann, har blackjack!";
    case BlackjackPush = "Resultat: Lika. Båda har blackjack!";
}

class Game
{
    private PlayerActions $player;
    private DealerActions $dealer;
    private DeckOfCards $deck;
    private GameStates $state = GameStates::NotStarted;

    public function __construct(PlayerActions $player = null, DealerActions $dealer = null, DeckOfCards $deck = null, GameStates $gameState = null)
    {
        $this->player = $player ?? new PlayerActions();
        $this->dealer = $dealer ?? new DealerActions();
        $this->deck = $deck ?? new DeckOfCards();
        $this->state = $gameState ?? GameStates::NotStarted;
    }

    public function getGameMessage(): string
    {
        return match($this->getState()) {
            GameStates::NotStarted => GameMessages::NotStarted->value,
            GameStates::PlayerTurn => GameMessages::PlayerTurn->value,
            GameStates::DealerTurn => GameMessages::DealerTurn->value,
            GameStates::Push => GameMessages::Push->value,
            GameStates::PlayerBusted => GameMessages::PlayerBusted->value,
            GameStates::DealerBusted => GameMessages::DealerBusted->value,
            GameStates::PlayerWin => GameMessages::PlayerWin->value,
            GameStates::DealerWin => GameMessages::DealerWin->value,
            GameStates::PlayerBlackjack => GameMessages::PlayerBlackjack->value,
            GameStates::DealerBlackjack => GameMessages::DealerBlackjack->value,
            GameStates::BlackjackPush => GameMessages::BlackjackPush->value,
        };
    }

    public function isGameDone(): bool
    {
        $state = $this->getState();

        $doneStates = [
         GameStates::PlayerBlackjack,
         GameStates::DealerBlackjack,
         GameStates::BlackjackPush,
         GameStates::Push,
         GameStates::PlayerBusted,
         GameStates::DealerBusted,
         GameStates::PlayerWin,
         GameStates::DealerWin,
        ];

        return in_array($state, $doneStates);
    }

    public function getState(): GameStates
    {
        return $this->state;
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

    public function setState(GameStates $state): void
    {
        $this->state = $state;
    }

    public function startGame(): void
    {
        if ($this->getState() == GameStates::NotStarted) {
            $this->deck->shuffle();

            // Försa kortet ut.
            $this->player->play(PlayerAction::HIT, $this->deck->drawCard());
            $this->dealer->play(PlayerAction::HIT, $this->deck->drawCard());

            // Andra kortet ut.
            $this->player->play(PlayerAction::HIT, $this->deck->drawCard());

            // Andra kortet för dealern och göm.
            $dealerSecondCard = $this->deck->drawCard();
            $dealerSecondCard ? $dealerSecondCard->setFaceDown(true) : null;
            $this->dealer->play(PlayerAction::HIT, $dealerSecondCard);

            // Set game state based on initial cards
            $this->setState($this->getAfterFlopState());
        }
    }

    public function getAfterFlopState(): GameStates
    {
        // Kolla om spelare har blackjack
        if ($this->player->getHand()->hasBlackJack()) {
            // Har båda blackjack??
            if ($this->dealer->getHand()->hasBlackJack()) {
                $this->revealDealerCard();
                return GameStates::BlackjackPush;
            }
            $this->revealDealerCard();
            return GameStates::PlayerBlackjack;

        }
        // Kolla om dealern har blackjack ensam
        if ($this->dealer->getHand()->hasBlackJack()) {
            $this->revealDealerCard();
            return GameStates::DealerBlackjack;
        }
        return GameStates::PlayerTurn;
    }

    public function getAfterGameState(): GameStates
    {
        // Denna kan inte bli player-bust. Detta hade skett när spelaren tog ett kort.
        if ($this->player->isBusted()) {
            return GameStates::PlayerBusted;
        }

        // Kolla om dealer har bustat
        if ($this->dealer->isBusted()) {
            return GameStates::DealerBusted;
        }

        // Kolla om push
        if ($this->player->getHand()->getTotalValue() == $this->dealer->getHand()->getTotalValue()) {
            return GameStates::Push;
        }

        // Spelaren med högst värde vinner.
        if ($this->player->getHand()->getTotalValue() > $this->dealer->getHand()->getTotalValue()) {
            return GameStates::PlayerWin;
        }
        return GameStates::DealerWin;
    }

    public function playerHit(): void
    {
        if ($this->getState() == GameStates::PlayerTurn) {
            $this->player->play(PlayerAction::HIT, $this->deck->drawCard());
            if ($this->player->isBusted()) {
                $this->setState(GameStates::PlayerBusted);
            }
        }
    }

    public function playerStand(): void
    {
        if ($this->getState() == GameStates::PlayerTurn) {
            $this->player->play(PlayerAction::STAND);
            $this->setState(GameStates::DealerTurn);
            $this->revealDealerCard();
            $this->dealerPlay();
        }
    }

    public function revealDealerCard(): void
    {
        $this->dealer->getHand()->getCards()[1]->setFaceDown(false);
    }

    public function dealerPlay(): void
    {
        if ($this->getState() == GameStates::DealerTurn) {

            // Dealer must hit on 16 or less, stand on 17 or more
            if ($this->dealer->getHandValue() <= 16) {
                $this->dealer->play(PlayerAction::HIT, $this->deck->drawCard());
            }
            if ($this->dealer->isBusted()) {
                $this->setState(GameStates::DealerBusted);
            } elseif ($this->dealer->getHandValue() >= 17) {
                $this->dealer->play(PlayerAction::STAND);
                $this->setState($this->getAfterGameState());
            }
            $this->dealerPlay();
        }
    }

    public function getResult(): GameStates
    {
        return $this->state;
    }


    public function getJSONGame(): string
    {
        $data = [
            'stateMessage' => $this->getGameMessage(),
            'player' => [
                'hand' => $this->player->getHand()->getJSONCards(),
                'totalValue' => $this->player->getHand()->getTotalValue(),
            ],
            'dealer' => [
                'hand' => $this->dealer->getHand()->getJSONCards(),
                'totalValue' => $this->dealer->getHand()->getTotalValue(),
            ],
        ];
        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    public function __toString(): string
    {
        return $this->getJSONGame();
    }
}
