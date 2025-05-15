<?php

namespace App\Card;

class DeckOfCards
{
    /** @var Card[] */
    private array $cards = [];

    public function __construct(array $cards = null)
    {
        if ($cards !== null) {
            foreach ($cards as $card) {
                if (!($card instanceof Card) && !($card instanceof CardGraphic)) {
                    throw new \InvalidArgumentException('Invalid card data: expected Card or CardGraphic object.');
                }
                $this->cards[] = $card;
            }
        } else {
            $this->initializeDeck();
        }
    }

    private function initializeDeck(): void
    {
        $this->cards = [];
        foreach (Card::getSuits() as $suit) {
            foreach (array_keys(Card::getValues()) as $value) {
                $this->cards[] = new CardGraphic($value, $suit);
            }
        }
    }

    public function getAmountOfCards(): int
    {
        return count($this->cards);
    }

    public function isEmpty(): bool
    {
        return empty($this->cards);
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function shuffle(): void
    {
        if (empty($this->cards)) {
            return;
        }
        shuffle($this->cards);

    }

    public function sort(): void
    {
        if (empty($this->cards)) {
            return;
        }
        usort($this->cards, function ($a, $b) {
            $suitOrder = ['Spades', 'Diamonds', 'Clubs', 'Hearts'];

            $suit = array_search($a->getSuit(), $suitOrder) - array_search($b->getSuit(), $suitOrder);
            if ($suit !== 0) {
                return $suit;
            }
            return $a->getValue() <=> $b->getValue();
        });
    }

    public function drawCard(): ?CardGraphic
    {
        if (empty($this->cards)) {
            return null;
        }
        return array_pop($this->cards);
    }

    public function getJSONDeck(): string
    {
        $jsonDeck = [];
        if (empty($this->cards)) {
            return json_encode($jsonDeck);
        }
        foreach ($this->cards as $card) {
            $jsonDeck[] = [
                'value' => $card->getValue(),
                'suit' => $card->getSuit(),
            ];
        }
        return json_encode($jsonDeck);
    }

    public function __toString(): string
    {
        $string = '';
        foreach ($this->cards as $card) {
            $string .= json_encode($card->getCard(), true);
        }
        return $string;
    }
}
