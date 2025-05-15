<?php

namespace App\Card;

use App\Card\CardGraphic;
use InvalidArgumentException;

class Card
{
    /**
     * Card constructor.
     *
     * @param int $val
     * @param string $suit
     * @throws InvalidArgumentException
     */
    protected int $value;
    protected string $suit;

    private const SUITS = [
        'Spades',
        'Diamonds',
        'Clubs',
        'Hearts'
    ];
    private const VALUES = [
        1 => 'Ace',
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 'Jack',
        12 => "Queen",
        13 => "King"
    ];

    public function __construct(int $val, string $suit)
    {
        $this->setValue($val);
        $this->setSuit($suit);
    }

    private function setSuit(string $suit): void
    {
        if (!in_array($suit, self::SUITS)) {
            throw new InvalidArgumentException('Invalid suit [Spades, Diamonds, Clubs, Hearts].');
        }

        $this->suit = $suit;
    }

    private function setValue(int $val): void
    {
        if ($val < 1 || $val > 13) {
            throw new InvalidArgumentException('Invalid value.');
        }

        $this->value = $val;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return array<string>
     */
    public static function getSuits(): array
    {
        return self::SUITS;
    }

    /**
     * @return array<int, string|int>
     */
    public static function getValues(): array
    {
        return self::VALUES;
    }

    /**
     * @return array{value: int, suit: string}
     */
    public function getCard(): array
    {
        return [
            'value' => $this->value,
            'suit' => $this->suit
        ];
    }

    public function __toString(): string
    {
        return self::VALUES[$this->value] . ' of ' . $this->suit;
    }
}
