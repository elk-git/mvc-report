<?php

namespace App\Card;

Class DeckOfCards
{
    /** @var Card[] */
    private array $cards;

    public function __construct()
    {
        $this->cards = [];
        foreach (Card::getSuits() as $suit) {
            foreach (array_keys(Card::getValues()) as $value) {
                $this->cards[] = new CardGraphic($value, $suit);
            }
        }
    }

    public function getDeck(): array
    {
        return $this->cards;
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