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

/**
 * Game instance creates a blackjack game. To start the game, call the startGame method.
 */
class Game
{
    /**
     * Player instance
     */
    private PlayerActions $player;
    /**
     * Dealer instance
     */
    private DealerActions $dealer;
    /**
     * DeckofCards instance
     */
    private DeckOfCards $deck;
    /**
     * Current game state - default is NotStarted.
     */
    private GameStates $state = GameStates::NotStarted;

    /**
     * Game constructor. If no arguments are provided, default values will be used.
     * @param PlayerActions|null $player Optional player instance
     * @param DealerActions|null $dealer Optional dealer instance
     * @param DeckOfCards|null $deck Optional deck instance
     * @param GameStates|null $gameState Optional initial game state
     */
    public function __construct(PlayerActions $player = null, DealerActions $dealer = null, DeckOfCards $deck = null, GameStates $gameState = null)
    {
        $this->player = $player ?? new PlayerActions();
        $this->dealer = $dealer ?? new DealerActions();
        $this->deck = $deck ?? new DeckOfCards();
        $this->state = $gameState ?? GameStates::NotStarted;
    }

    /**
     * Get the current game message that corresponds to the current game state..
     * @return string
     */
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

    /**
     * Check if the game is done.
     * @return bool
     */
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

    /**
     * Get the current game state.
     * @return GameStates
     */
    public function getState(): GameStates
    {
        return $this->state;
    }

    /**
     * Get the player instance.
     * @return PlayerActions
     */
    public function getPlayer(): PlayerActions
    {
        return $this->player;
    }

    /**
     * Get the dealer instance.
     * @return DealerActions
     */
    public function getDealer(): DealerActions
    {
        return $this->dealer;
    }

    /**
     * Get the deck instance.
     * @return DeckOfCards
     */
    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }

    /**
     * Set the current game state.
     * @param GameStates $state
     * @return void
     */
    public function setState(GameStates $state): void
    {
        $this->state = $state;
    }

    /**
     * Start the game. It will shuffle the deck and deal the starting cards (two each and dealer one face down). It will change the game state to PlayerTurn.
     * @return void
     */
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

    /**
     * Get GameState after "the flop", i.e after the startGame method i.e after giving first to cards to each player. It will check for dealer blackjack with the face-down card aswell.
     * @return GameStates
     */
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

    /**
     * Get GameState after the dealer has played.
     * @return GameStates
     */
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

    /**
     * Method will let the player "hit" (draw a card). It will update the gamestate accordingly if the player busts.
     * @return void
     */
    public function playerHit(): void
    {
        if ($this->getState() == GameStates::PlayerTurn) {
            $this->player->play(PlayerAction::HIT, $this->deck->drawCard());
            if ($this->player->isBusted()) {
                $this->setState(GameStates::PlayerBusted);
            }
        }
    }

    /**
     * Method will let the player "stand" (not draw a card). It will update the gamestate to dealer turn. And call the dealerplay method.
     * @return void
     */
    public function playerStand(): void
    {
        if ($this->getState() == GameStates::PlayerTurn) {
            $this->player->play(PlayerAction::STAND);
            $this->setState(GameStates::DealerTurn);
            $this->revealDealerCard();
            $this->dealerPlay();
        }
    }

    /**
     * Method will reveal the dealer's second card.
     * @return void
     */
    public function revealDealerCard(): void
    {
        $this->dealer->getHand()->getCards()[1]->setFaceDown(false);
    }

    /**
     * Method will let the dealer "play" (draw cards). It will update the gamestate accordingly if the dealer busts or stands. Dealer hits until 17 or more. Else dealer stands.
     * @return void
     */
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

    /**
     * Get the result of the game in a GameState.
     * @return GameStates
     */
    public function getResult(): GameStates
    {
        return $this->state;
    }

    /**
     * Get the game in a JSON format. It will return the gamestatemessage, the players hand and total value, and the dealers hand an total value.
     * @return string
     */
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

    /**
     * Get the game in a string format. It will call the JSONGame method.
     * @return string
     */
    public function __toString(): string
    {
        return $this->getJSONGame();
    }
}
