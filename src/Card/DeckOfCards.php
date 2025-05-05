<?php

namespace App\Card;

Class DeckOfCards
{
    protected array $deck = [];
    protected array $suits = ['Heart', 'Diamond', 'Club', 'Spade'];
    protected array $values = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];

    public function __construct()
    {
        foreach ($this->suits as $suit) {
            foreach ($this->values as $value) {
                $this->deck[] = new Card($value, $suit);
            }
        }
    }
}