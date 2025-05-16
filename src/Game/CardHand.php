<?php

namespace App\Game;

class CardHand
{
    /** @var array<Card|CardGraphic> */
    protected array $cards = [];


    private const VALUES = [
        1 => 11, // This could be 1 or 11. 1 is handled in the getTotalValue method.
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 10,
        12 => 10,
        13 => 10
    ];
    private const BLACKJACK_VALUE = 21;

    /**
     * @param array<Card|CardGraphic> $cards
     */
    public function __construct(array $cards = null)
    {
        // No type check needed as we are using the Card class.
        $this->cards = $cards ?? [];
    }

    /**
     * @return array<Card|CardGraphic>
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * @param Card|CardGraphic $card
     * @return void
     */
    public function addCard(Card|CardGraphic $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * @return int
     * // Need to incorporate aces... either 1 or 11.
     */
    public function getTotalValue(): int
    {
        $totalValue = 0;
        $aces = [];

        foreach ($this->cards as $card) {
            if ($card->isFaceDown()) {
                continue;
            }
            if ($card->getValue() === 1) { // Ace
                $aces[] = $card;
                continue;
            }
            $totalValue += self::VALUES[$card->getValue()];

        }

        // Lägg till aces 11 om det går annars 1.
        foreach ($aces as $key => $ace) {
            $ace = $ace;
            if ($totalValue + 11 <= self::BLACKJACK_VALUE && $key === count($aces) - 1) {
                $totalValue += 11;
                continue;
            }
            $totalValue += 1;

        }

        return $totalValue;
    }

    public function getTotalValueWithFaceDown(): int
    {
        $totalValue = 0;
        $aces = [];

        foreach ($this->cards as $card) {
            if ($card->getValue() === 1) { // Ace
                $aces[] = $card;
                continue;
            }
            $totalValue += self::VALUES[$card->getValue()];
        }

        // Lägg till aces 11 om det går annars 1.
        foreach ($aces as $key => $ace) {
            $ace = $ace;
            if ($totalValue + 11 <= self::BLACKJACK_VALUE && $key === count($aces) - 1) {
                $totalValue += 11;
                continue;
            }
            $totalValue += 1;
        }

        return $totalValue;
    }


    public function hasBlackJack(): bool
    {
        if ($this->getTotalValueWithFaceDown() === self::BLACKJACK_VALUE) {
            return true;
        }
        return false;
    }

    /**
     * @return array<array{value: int, suit: string, isFaceDown: bool}>
     */
    public function getJSONCards(): array
    {
        return array_map(fn ($card) => $card->getCard(), $this->cards);
    }
}
