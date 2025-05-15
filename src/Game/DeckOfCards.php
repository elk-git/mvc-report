<?php

namespace App\Game;

class DeckOfCards
{
    /** @var array<Card|CardGraphic> */
    private array $cards = [];

    /**
     * @param array<Card|CardGraphic>|null $cards
     */
    public function __construct(?array $cards = null)
    {
        if ($cards !== null) {
            foreach ($cards as $card) {
                // Tyligen vill inte phpstan ha typecheck här då vi alltid använder klasserna...
                $this->cards[] = $card;
            }
            return;
        }
        $this->initializeDeck();
    }

    /**
     * @return void
     */
    private function initializeDeck(): void
    {
        $this->cards = [];
        $card = new Card(1, 'Spades'); // Create instance to access constants
        foreach ($card->getSuits() as $suit) {
            foreach (array_keys($card->getValues()) as $value) {
                $this->cards[] = new CardGraphic($value, $suit);
            }
        }
    }

    /**
     * @return int
     */
    public function getAmountOfCards(): int
    {
        return count($this->cards);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->cards);
    }

    /**
     * @return array<Card|CardGraphic>
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * @return void
     */
    public function shuffle(): void
    {
        if (empty($this->cards)) {
            return;
        }
        shuffle($this->cards);
    }

    /**
     * @return void
     */
    public function sort(): void
    {
        if (empty($this->cards)) {
            return;
        }
        usort($this->cards, function ($firstCard, $secondCard) {
            $suitOrder = ['Spades', 'Diamonds', 'Clubs', 'Hearts'];

            $suit = array_search($firstCard->getSuit(), $suitOrder) - array_search($secondCard->getSuit(), $suitOrder);
            if ($suit !== 0) {
                return $suit;
            }
            return $firstCard->getValue() <=> $secondCard->getValue();
        });
    }

    /**
     * @return CardGraphic|null
     */
    public function drawCard(): ?CardGraphic
    {
        if (empty($this->cards)) {
            return null;
        }
        $card = array_pop($this->cards);
        return $card instanceof CardGraphic ? $card : new CardGraphic($card->getValue(), $card->getSuit());
    }

    /**
     * @return string
     */
    public function getJSONDeck(): string
    {
        $jsonDeck = [];
        if (empty($this->cards)) {
            return json_encode($jsonDeck, JSON_THROW_ON_ERROR);
        }
        foreach ($this->cards as $card) {
            $jsonDeck[] = [
                'value' => $card->getValue(),
                'suit' => $card->getSuit(),
            ];
        }
        return json_encode($jsonDeck, JSON_THROW_ON_ERROR);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $string = '';
        foreach ($this->cards as $card) {
            $string .= json_encode($card->getCard(), JSON_THROW_ON_ERROR);
        }
        return $string;
    }
}
