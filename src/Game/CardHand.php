<?php

namespace App\Game;

class CardHand
{
    /** @var array<Card|CardGraphic> */
    protected array $cards = [];

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
            $totalValue += $card->getValue();
        }
        return $totalValue;
    }
}
