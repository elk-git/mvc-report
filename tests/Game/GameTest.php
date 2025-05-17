<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;
use App\Game\Game;
use App\Game\GameStates;
use App\Game\DeckOfCards;
use App\Game\PlayerActions;
use App\Game\DealerActions;

/**
 * @SuppressWarnings("PHPMD.TooManyPublicMethods")
 */
class GameTest extends TestCase
{
    public function testGame(): void
    {
        $game = new Game();
        $this->assertInstanceOf(Game::class, $game);
    }

    public function testGameStartGame(): void
    {
        // För att det inte ska bli blackjack direkt.
        $game = new Game(
            player: null,
            dealer: null,
            deck: new DeckOfCards([new Card(2, 'Spades'), new Card(2, 'Hearts'), new Card(2, 'Clubs'), new Card(2, 'Diamonds')]),
        );
        $game->startGame();
        $this->assertInstanceOf(Game::class, $game);
        $this->assertEquals(GameStates::PlayerTurn, $game->getState());
        $this->assertInstanceOf(DeckOfCards::class, $game->getDeck());
        $this->assertInstanceOf(PlayerActions::class, $game->getPlayer());
        $this->assertInstanceOf(DealerActions::class, $game->getDealer());
        $this->assertInstanceOf(GameStates::class, $game->getState());
    }

    public function testGameIsDoneFalse(): void
    {
        $game = new Game(
            player: null,
            dealer: null,
            deck: new DeckOfCards([new Card(2, 'Spades'), new Card(2, 'Hearts'), new Card(2, 'Diamonds'), new Card(2, 'Clubs')])
        );
        $game->startGame();
        $this->assertFalse($game->isGameDone());
    }

    public function testGameIsDoneTrue(): void
    {
        $game = new Game();
        $game->startGame();
        $game->setState(GameStates::PlayerBlackjack);
        $this->assertTrue($game->isGameDone());
    }

    public function testGameGetState(): void
    {
        $game = new Game();
        $game->startGame();
        $game->setState(GameStates::NotStarted);
        $this->assertEquals(GameStates::NotStarted, $game->getState());
    }

    public function testGetGameMessage(): void
    {
        // För att det inte ska bli blackjack direkt.
        $game = new Game(
            player: null,
            dealer: null,
            deck: new DeckOfCards([new Card(2, 'Spades'), new Card(2, 'Hearts'), new Card(2, 'Clubs'), new Card(2, 'Diamonds')]),
        );
        $this->assertEquals(GameMessages::NotStarted->value, $game->getGameMessage());
        $game->startGame();
        $this->assertEquals(GameMessages::PlayerTurn->value, $game->getGameMessage());
        $game->setState(GameStates::DealerTurn);
        $this->assertEquals(GameMessages::DealerTurn->value, $game->getGameMessage());
        $game->setState(GameStates::Push);
        $this->assertEquals(GameMessages::Push->value, $game->getGameMessage());
        $game->setState(GameStates::PlayerBusted);
        $this->assertEquals(GameMessages::PlayerBusted->value, $game->getGameMessage());
        $game->setState(GameStates::DealerBusted);
        $this->assertEquals(GameMessages::DealerBusted->value, $game->getGameMessage());
        $game->setState(GameStates::PlayerWin);
        $this->assertEquals(GameMessages::PlayerWin->value, $game->getGameMessage());
        $game->setState(GameStates::DealerWin);
        $this->assertEquals(GameMessages::DealerWin->value, $game->getGameMessage());
        $game->setState(GameStates::PlayerBlackjack);
        $this->assertEquals(GameMessages::PlayerBlackjack->value, $game->getGameMessage());
        $game->setState(GameStates::DealerBlackjack);
        $this->assertEquals(GameMessages::DealerBlackjack->value, $game->getGameMessage());
        $game->setState(GameStates::BlackjackPush);
        $this->assertEquals(GameMessages::BlackjackPush->value, $game->getGameMessage());
    }

    public function mockGameStart(Game $game): Game
    {
        $game->getPlayer()->play(PlayerAction::HIT, $game->getDeck()->drawCard());
        $game->getDealer()->play(PlayerAction::HIT, $game->getDeck()->drawCard());
        $game->getPlayer()->play(PlayerAction::HIT, $game->getDeck()->drawCard());
        $game->getDealer()->play(PlayerAction::HIT, $game->getDeck()->drawCard());
        return $game;
    }

    public function testGameAfterFlopStateBlackjackPush(): void
    {
        $game = new Game(
            player: null,
            dealer: null,
            deck: new DeckOfCards([new Card(10, 'Spades'), new Card(10, 'Hearts'), new Card(1, 'Clubs'), new Card(1, 'Diamonds')]),
        );

        $game = $this->mockGameStart($game);

        $this->assertEquals(GameStates::BlackjackPush, $game->getAfterFlopState());
    }

    public function testGameAfterFlopStateDealerBlackjack(): void
    {
        $deck = new DeckOfCards([new Card(1, 'Spades'), new Card(2, 'Hearts'), new Card(10, 'Clubs'), new Card(2, 'Diamonds')]);
        $game = new Game(
            player: null,
            dealer: null,
            deck: $deck,
        );

        $game = $this->mockGameStart($game);

        $this->assertEquals(GameStates::DealerBlackjack, $game->getAfterFlopState());
    }

    public function testGameAfterFlopStatePlayerBlackjack(): void
    {
        $deck = new DeckOfCards([new Card(2, 'Spades'), new Card(1, 'Hearts'), new Card(2, 'Clubs'), new Card(10, 'Diamonds')]);
        $game = new Game(
            player: null,
            dealer: null,
            deck: $deck,
        );

        $game = $this->mockGameStart($game);

        $this->assertEquals(GameStates::PlayerBlackjack, $game->getAfterFlopState());
    }

    public function testGameAfterGameStatePlayerBusted(): void
    {
        $deck = new DeckOfCards([new Card(9, 'Diamonds'), new Card(2, 'Spades'), new Card(9, 'Hearts'), new Card(2, 'Clubs'), new Card(10, 'Diamonds')]);
        $game = new Game(
            player: null,
            dealer: null,
            deck: $deck,
        );
        $game = $this->mockGameStart($game);
        $game->setState($game->getAfterFlopState());
        $game->playerHit();
        $this->assertEquals(GameStates::PlayerBusted, $game->getAfterGameState());
    }

    public function testGameAfterGameStateDealerBusted(): void
    {
        $deck = new DeckOfCards([new Card(9, 'Diamonds'), new Card(7, 'Spades'), new Card(2, 'Hearts'), new Card(9, 'Clubs'), new Card(2, 'Diamonds')]);
        $game = new Game(
            player: null,
            dealer: null,
            deck: $deck,
        );
        $game = $this->mockGameStart($game);
        $game->setState($game->getAfterFlopState());
        $game->playerStand();
        $this->assertEquals(GameStates::DealerBusted, $game->getAfterGameState());
    }

    public function testGameAfterGameStatePush(): void
    {
        $deck = new DeckOfCards([new Card(7, 'Spades'), new Card(10, 'Hearts'), new Card(10, 'Clubs'), new Card(7, 'Diamonds')]);
        $game = new Game(
            player: null,
            dealer: null,
            deck: $deck,
        );
        $game = $this->mockGameStart($game);
        $game->setState($game->getAfterFlopState());
        $game->playerStand();
        $this->assertEquals(GameStates::Push, $game->getAfterGameState());
    }

    public function testGameAfterGameStatePlayerWin(): void
    {
        $deck = new DeckOfCards([new Card(7, 'Spades'), new Card(10, 'Hearts'), new Card(10, 'Clubs'), new Card(8, 'Diamonds')]);
        $game = new Game(
            player: null,
            dealer: null,
            deck: $deck,
        );
        $game = $this->mockGameStart($game);
        $game->setState($game->getAfterFlopState());
        $game->playerStand();
        $this->assertEquals(GameStates::PlayerWin, $game->getAfterGameState());
    }

    public function testGameAfterGameStateDealerWin(): void
    {
        $deck = new DeckOfCards([new Card(8, 'Spades'), new Card(10, 'Hearts'), new Card(10, 'Clubs'), new Card(7, 'Diamonds')]);
        $game = new Game(
            player: null,
            dealer: null,
            deck: $deck,
        );
        $game = $this->mockGameStart($game);
        $game->setState($game->getAfterFlopState());
        $game->playerStand();
        $this->assertEquals(GameStates::DealerWin, $game->getAfterGameState());
    }

    public function testGameToString(): void
    {
        $game = new Game();
        $result = $game->__toString();
        $this->assertNotEmpty($result);
    }

    public function testGameResult(): void
    {
        $game = new Game();
        $game->setState(GameStates::PlayerBusted);
        $this->assertEquals(GameStates::PlayerBusted, $game->getResult());
    }
}
