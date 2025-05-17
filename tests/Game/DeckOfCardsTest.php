<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;
use App\Game\DeckOfCards;

/**
 * @SuppressWarnings("PHPMD.TooManyPublicMethods")
 */
class DeckOfCardsTest extends TestCase
{
    public function testDeckOfCards(): void
    {
        $deck = new DeckOfCards();
        $this->assertInstanceOf(DeckOfCards::class, $deck);
    }

    public function testDeckOfCardsEquals52Cards(): void
    {
        $deck = new DeckOfCards();
        $this->assertCount(52, $deck->getCards());
        $this->assertContainsOnlyInstancesOf(CardGraphic::class, $deck->getCards());
        $this->assertEquals(52, $deck->getAmountOfCards());
    }

    public function testDeckOfCardsWithArgs2Cards(): void
    {
        $args = [new Card(1, 'Spades'), new Card(10, 'Spades')];
        $deck = new DeckOfCards($args);
        $this->assertCount(2, $deck->getCards());
        $this->assertEquals($args[0], $deck->getCards()[0]);
        $this->assertEquals($args[1], $deck->getCards()[1]);
    }

    public function testDeckOfCardsDrawCard(): void
    {
        $deck = new DeckOfCards();
        $card = $deck->drawCard();
        $this->assertInstanceOf(CardGraphic::class, $card);
        $this->assertCount(51, $deck->getCards());
    }

    public function testDeckOfCardsEmpty(): void
    {
        $deck = new DeckOfCards([]);
        $this->assertCount(0, $deck->getCards());
        $this->assertEmpty($deck->getCards());
        $this->assertEquals(0, $deck->getAmountOfCards());
        $this->assertNull($deck->drawCard());
        $this->assertTrue($deck->isEmpty());
    }

    public function testDeckOfCardsShuffle(): void
    {
        $deck = new DeckOfCards();
        $cards = $deck->getCards();
        $deck->shuffle();
        $this->assertNotEquals($cards, $deck->getCards());
    }

    public function testDeckOfCardsShuffleWhen1Card(): void
    {
        $deck = new DeckOfCards([new Card(1, 'Spades')]);
        $cards = $deck->getCards();
        $deck->shuffle();
        $this->assertEquals($cards, $deck->getCards());
    }

    public function testDeckOfCardsShuffleWhenEmpty(): void
    {
        $deck = new DeckOfCards([]);
        $deck->shuffle();
        $this->assertEquals([], $deck->getCards());
    }

    public function testDeckOfCardsSort(): void
    {
        $deck = new DeckOfCards();
        $sortedCards = $deck->getCards();
        $deck2 = new DeckOfCards();
        $deck2->shuffle();
        $deck2->shuffle();
        $shuffledCards = $deck2->getCards();
        $this->assertNotEquals($shuffledCards, $sortedCards);
        $deck2->sort();
        $this->assertEquals($sortedCards, $deck2->getCards());
    }

    public function testDeckOfCardsSortNothing(): void
    {
        $deck = new DeckOfCards([]);
        $deck->sort();
        $this->assertEquals([], $deck->getCards());
    }

    public function testDeckOfCardsGetJSONDeck(): void
    {
        $deck = new DeckOfCards();
        $jsonDeck = $deck->getJSONDeck();
        $this->assertJson($jsonDeck);
    }

    public function testDeckOfCardsGetJSONDeckNoCards(): void
    {
        $deck = new DeckOfCards([]);
        $jsonDeck = $deck->getJSONDeck();
        $this->assertJson($jsonDeck);
    }

    public function testDeckOfCardsToString(): void
    {
        $deck = new DeckOfCards();
        $this->assertNotEmpty($deck->__toString());
    }
}
