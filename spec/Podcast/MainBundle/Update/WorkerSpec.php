<?php

namespace spec\Podcast\MainBundle\Update;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WorkerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Podcast\MainBundle\Update\Worker');
    }


}
