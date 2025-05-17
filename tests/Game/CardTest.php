<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;
use ArgumentCountError;
use InvalidArgumentException;
use App\Game\Card;

class CardTest extends TestCase
{
    public function testCardInvalidSuit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Card(1, 'Invalid');
    }

    public function testCardInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Card(14, 'Spades');
    }

    public function testCardValid(): void
    {
        $card = new Card(1, 'Spades');
        $this->assertEquals(1, $card->getValue());
        $this->assertEquals('Spades', $card->getSuit());
        $this->assertInstanceOf(Card::class, $card);
    }

    public function testCardIsFaceDown(): void
    {
        $card = new Card(1, 'Spades');
        $this->assertFalse($card->isFaceDown());
        $card->setFaceDown(true);
        $this->assertTrue($card->isFaceDown());
    }

    public function testCardGetSuits(): void
    {
        $card = new Card(1, 'Spades');
        $suits = $card->getSuits();
        $this->assertCount(4, $suits);
        $this->assertContains('Spades', $suits);
        $this->assertContains('Hearts', $suits);
        $this->assertContains('Diamonds', $suits);
        $this->assertContains('Clubs', $suits);
    }

    public function testCardGetValues(): void
    {
        $card = new Card(1, 'Spades');
        $values = $card->getValues();
        $this->assertCount(13, $values);
        $this->assertContains('Ace', $values);
        $this->assertContains('King', $values);
    }

    public function testCardGetCard(): void
    {
        $card = new Card(1, 'Spades');
        $this->assertEquals('Spades', $card->getCard()['suit']);
        $this->assertEquals(1, $card->getCard()['value']);
        $this->assertEquals(false, $card->getCard()['isFaceDown']);
    }

    public function testCardSuits(): void
    {
        $card = new Card(1, 'Spades');
        $suits = $card->getSuits();
        $this->assertEquals($card->getSuits(), $suits);
    }

    public function testCardValues(): void
    {
        $card = new Card(1, 'Spades');
        $values = $card->getValues();
        $this->assertEquals($card->getValues(), $values);
    }

    public function testGetSuitFaceDown(): void
    {
        $card = new Card(1, 'Spades');
        $card->setFaceDown(true);
        $this->assertEquals('face-down', $card->getSuit());
    }

}
