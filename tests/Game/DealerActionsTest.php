<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;
use App\Game\DealerActions;
use App\Game\DeckOfCards;

class DealerActionsTest extends TestCase
{
    public function testDealerActions(): void
    {
        $dealerActions = new DealerActions();
        $this->assertInstanceOf(DealerActions::class, $dealerActions);
    }

    public function testDealerActionsPlayStand(): void
    {
        $dealerActions = new DealerActions();
        $deck = new DeckOfCards();

        $hand = $dealerActions->getHand();
        $hand->addCard(new Card(1, 'Spades'));
        $hand->addCard(new Card(10, 'Spades'));

        $dealerActions->play(PlayerAction::HIT, $deck->drawCard());
        $this->assertEquals(21, $dealerActions->getHand()->getTotalValue());
    }

    public function testDealerActionsPlayHit(): void
    {
        $dealerActions = new DealerActions();

        $hand = $dealerActions->getHand();
        $hand->addCard(new Card(1, 'Spades'));

        $dealerActions->play(PlayerAction::HIT, new CardGraphic(1, 'Spades'));
        $this->assertEquals(12, $dealerActions->getHand()->getTotalValue());
    }

}
