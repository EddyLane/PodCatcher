<?php

namespace spec;

use PHPSpec2\ObjectBehavior;

class Payment extends ObjectBehavior
{
    
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Payment');
    }
    
    
    function it_should_not_be_paid_by_default()
    {
        $this->shouldNotBePaid();
    }
    
    /**
     * @param Card\Mastercard $mastercard
     */
    function it_should_not_accept_mastercard($mastercard)
    {
        $this->accepts($mastercard)->shouldReturn(false);
    }
    
    /**
     * @param Card\Visa $visa
     */
    function it_should_accept_visa($visa)
    {
        $this->accepts($visa)->shouldReturn(true);
    }
    
    
    /**
     * @param Card\Visa $visa
     */
    function it_should_be_payable_through_visa($visa)
    {
        $this->payWith($visa);
        $this->shouldBePaid();
    }
    
}
