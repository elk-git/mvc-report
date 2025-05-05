<?php

namespace App\Card;

class CardGraphic extends Card
{
    public function getUnicodeCard(): string
    {
        $unicodeMap = [
            'Spades' => [
                1 => "\u{1F0A1}", // A
                2 => "\u{1F0A2}",
                3 => "\u{1F0A3}",
                4 => "\u{1F0A4}",
                5 => "\u{1F0A5}",
                6 => "\u{1F0A6}",
                7 => "\u{1F0A7}",
                8 => "\u{1F0A8}",
                9 => "\u{1F0A9}",
                10 => "\u{1F0AA}",
                11 => "\u{1F0AB}",
                12 => "\u{1F0AD}",
                13 => "\u{1F0AE}",
            ],
            'Hearts' => [
                1 => "\u{1F0B1}",
                2 => "\u{1F0B2}",
                3 => "\u{1F0B3}",
                4 => "\u{1F0B4}",
                5 => "\u{1F0B5}",
                6 => "\u{1F0B6}",
                7 => "\u{1F0B7}",
                8 => "\u{1F0B8}",
                9 => "\u{1F0B9}",
                10 => "\u{1F0BA}",
                11 => "\u{1F0BB}",
                12 => "\u{1F0BD}",
                13 => "\u{1F0BE}",
            ],
            'Clubs' => [
                1 => "\u{1F0D1}", // A
                2  => "\u{1F0D2}",
                3  => "\u{1F0D3}",
                4  => "\u{1F0D4}",
                5  => "\u{1F0D5}",
                6  => "\u{1F0D6}",
                7  => "\u{1F0D7}",
                8  => "\u{1F0D8}",
                9  => "\u{1F0D9}",
                10 => "\u{1F0DA}",
                11 => "\u{1F0DB}",
                12 => "\u{1F0DD}",
                13 => "\u{1F0DE}",
            ],
            'Diamonds' => [
                1 => "\u{1F0C1}", // A
                2  => "\u{1F0C2}",
                3  => "\u{1F0C3}",
                4  => "\u{1F0C4}",
                5  => "\u{1F0C5}",
                6  => "\u{1F0C6}",
                7  => "\u{1F0C7}",
                8  => "\u{1F0C8}",
                9  => "\u{1F0C9}",
                10 => "\u{1F0CA}",
                11 => "\u{1F0CB}",
                12 => "\u{1F0CD}",
                13 => "\u{1F0CE}",
            ],
        ];

        $suit = $this->getSuit();
        $value = $this->getValue();

        return $unicodeMap[$suit][$value] ?? '?';
    }
}
