<?php

namespace spec\Card;

use PHPSpec2\ObjectBehavior;

class Visa extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Card\Visa');
    }
}
