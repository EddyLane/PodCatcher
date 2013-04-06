<?php

namespace Podcast\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PodcastUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
