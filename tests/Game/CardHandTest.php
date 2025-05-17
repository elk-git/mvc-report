<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

class CardHandTest extends TestCase
{
    public function testCardHandNoArgsIsEmptyArray(): void
    {
        $cardHand = new CardHand();
        $this->assertEmpty($cardHand->getCards());
    }

    public function testCardHandAddCard(): void
    {
        $cardHand = new CardHand();
        $card = new Card(1, 'Spades');
        $cardHand->addCard($card);
        $this->assertCount(1, $cardHand->getCards());
        $this->assertEquals($card, $cardHand->getCards()[0]);
    }

    public function testCardHandGetTotalValue(): void
    {
        $cardHand = new CardHand();
        $card = new Card(1, 'Spades');
        $cardHand->addCard($card);
        $this->assertEquals(11, $cardHand->getTotalValue());

        $card = new Card(10, 'Spades');
        $cardHand->addCard($card);
        $this->assertEquals(21, $cardHand->getTotalValue());
    }

    public function testCardHandAceIs11(): void
    {
        $cardHand = new CardHand();
        $card = new Card(1, 'Spades');
        $cardHand->addCard($card);
        $this->assertEquals(11, $cardHand->getTotalValue());
    }

    public function testCardHandTotalValueWithoutFaceDownCards(): void
    {
        $cardHand = new CardHand();
        $card = new Card(1, 'Spades');
        $cardHand->addCard($card);
        $this->assertEquals(11, $cardHand->getTotalValue());

        $card->setFaceDown(true);
        $this->assertEquals(0, $cardHand->getTotalValue());
    }

    public function testCardHandGetTotalValueWithFaceDownCards(): void
    {
        $cardHand = new CardHand();
        $card = new Card(1, 'Spades');
        $card2 = new Card(10, 'Spades');
        $card->setFaceDown(true);
        $cardHand->addCard($card);
        $cardHand->addCard($card2);
        $this->assertEquals(10, $cardHand->getTotalValue());
        $this->assertEquals(21, $cardHand->getTotalValueWithFaceDown());

        $card3 = new Card(1, 'Hearts');
        $cardHand->addCard($card3);
        $this->assertEquals(21, $cardHand->getTotalValue());
        $this->assertEquals(12, $cardHand->getTotalValueWithFaceDown());
    }

    public function testCardHandAceIs1(): void
    {
        $cardHand = new CardHand();
        $card = new Card(1, 'Spades');
        $cardHand->addCard($card);
        $card2 = new Card(1, 'Hearts');
        $cardHand->addCard($card2);
        $this->assertEquals(12, $cardHand->getTotalValue());
    }

    public function testCardHandTotalValueAces(): void
    {
        $cardHand = new CardHand();
        $card = new Card(1, 'Spades');
        $cardHand->addCard($card);
        $this->assertEquals(11, $cardHand->getTotalValue());

        $card2 = new Card(1, 'Hearts');
        $cardHand->addCard($card2);
        $this->assertEquals(12, $cardHand->getTotalValue());

        $card3 = new Card(10, 'Spades');
        $cardHand->addCard($card3);
        $this->assertEquals(12, $cardHand->getTotalValue());

        $card4 = new Card(1, 'Spades');
        $cardHand->addCard($card4);
        $this->assertEquals(13, $cardHand->getTotalValue());
    }

    public function testCardHandHasBlackJack(): void
    {
        $cardHand = new CardHand();
        $card = new Card(1, 'Spades');
        $cardHand->addCard($card);
        $this->assertFalse($cardHand->hasBlackJack());

        $card2 = new Card(10, 'Spades');
        $cardHand->addCard($card2);
        $this->assertTrue($cardHand->hasBlackJack());

        $card3 = new Card(1, 'Hearts');
        $cardHand->addCard($card3);
        $this->assertFalse($cardHand->hasBlackJack());
    }

    public function testCardHandGetJSONCards(): void
    {
        $cardHand = new CardHand();
        $card = new Card(1, 'Spades');
        $cardHand->addCard($card);
        $this->assertEquals([['value' => 1, 'suit' => 'Spades', 'isFaceDown' => false]], $cardHand->getJSONCards());
    }
}
