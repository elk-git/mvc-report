<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

class CardGraphicTest extends TestCase
{
    // Varför är phpstan inte ok med att checka basic grejer????? obv kommer alltid en sträng returneras men men.
    // public function testCardGraphicGetUnicodeCardIsString(): void
    // {
    //     $card = new CardGraphic(1, 'Spades');
    //     $return = $card->getUnicodeCard();
    //     $this->assertIsString($return);
    // }

    public function testCardGraphicGetUnicodeCardIsFaceDown(): void
    {
        $card = new CardGraphic(1, 'Spades');
        $card->setFaceDown(true);
        $return = $card->getUnicodeCard();
        $this->assertEquals("\u{1F0A0}", $return);
    }
}
