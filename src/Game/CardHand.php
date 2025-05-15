<?php

namespace App\Game;

class CardHand
{
    /** @var array<Card|CardGraphic> */
    protected array $cards = [];


    private const VALUES = [
        'Ace' => 11, // This could be 1 or 11. 1 is handled in the getTotalValue method.
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
        foreach ($this->cards as $card) {
            $cardValue = self::VALUES[$card->getValue()];
            if ($totalValue + $cardValue > self::BLACKJACK_VALUE && $card->getValue() === 'Ace') {
                $cardValue = 1;
            }
            $totalValue += $cardValue;

            // NOTERA ATT DETTA ENDAST GÖRS PÅ DETTA KORT. MÅSTE FIXA SÅ DET ALLTID BLIR SÅ!!!!!
        }
        return $totalValue;
    }


    public function hasBlackJack(): bool
    {
        if ($this->getTotalValue() === self::BLACKJACK_VALUE) {
            return true;
        }
        return false;
    }
}
