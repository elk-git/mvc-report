<?php

namespace App\Card;

class Card
{
    /**
     * Card constructor.
     *
     * @param int $val
     * @param string $suit
     * @throws \InvalidArgumentException
     */
    protected $value;
    protected $suit;

    private const SUITS = [
        'Heart' => "\u{2665}",
        'Diamond' => "\u{2666}",
        'Club' => "\u{2663}",
        'Spade' => "\u{2660}"
    ];
    private const VALUES = [
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 'J',
        12 => "\u{2655}",
        13 => "\u{265A}",
        14 => 'A'
    ];

    public function __construct($val, $suit)
    {
        $this->setValue($val);
        $this->setSuit($suit);
    }
    private function setSuit($suit)
    {
        $SUITS = array_keys(self::SUITS);
        if (!in_array($suit, $SUITS)) {
            throw new \InvalidArgumentException('Invalid suit [Heart, Diamond, Club, Spade].');
        }

        $this->suit = $suit;
    }

    private function setValue($val)
    {
        if (!is_int($val) || $val < 2 || $val > 14) {
            throw new \InvalidArgumentException('Invalid value provided.');
        }

        $this->value = $val;
    }

    public function getSuit()
    {
        return $this->suit;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getCard(): array
    {
        return [
            'value' => self::VALUES[$this->value],
            'suit' => self::SUITS[$this->suit]
        ];
    }

    public function __toString()
    {
        return self::values[$this->value] . ' of ' . slef::suits[$this->suit];
    }
}
