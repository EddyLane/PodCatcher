<?php

class Payment
{
    private $paid = false;

    public function isPaid()
    {
        return $this->paid;
    }

    public function accepts($card)
    {
        return $card instanceof Card\Visa;
    }

    public function payWith($card)
    {
        $this->paid = true;
    }
}
