<?php

namespace App\Card;

Class Card
{
    protected $value;

    public function __construct($val)
    {
        $this->value = $val or 2;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}