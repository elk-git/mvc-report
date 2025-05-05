<?php

namespace App\Card;

class DeckOfCards
{
    /** @var Card[] */
    private array $cards;

    public function __construct(array $cardsJSON = null)
    {
        if ($cardsJSON !== null) {
            foreach ($cardsJSON as $card) {
                if (!isset($card['value']) || !isset($card['suit'])) {
                    throw new \InvalidArgumentException('Invalid card data.');
                }
                $this->cards[] = new CardGraphic($card['value'], $card['suit']);
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

    public function getCards(): array
    {
        return $this->cards;
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
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
        foreach ($this->cards as $card) {
            $jsonDeck[] = [
                'value' => $card->getValue(),
                'suit' => $card->getSuit(),
            ];
        }
        return json_encode($jsonDeck);
    }
}
