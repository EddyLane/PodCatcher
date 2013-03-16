<?php

namespace Podcast\MainBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PodcastMainBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }

}
