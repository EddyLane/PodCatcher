<?php

namespace Podcast\MainBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Podcast\MainBundle\DataFixtures\ORM\Loader;

class LoadAll extends Loader 
{
    
    /**
     * Loads fixtures for pages and navigation
     * 
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager) 
    {
        $this->loadFixtures(array(
            'PodcastMainBundle:Podcast.yml',
            'PodcastMainBundle:Category.yml',
            'PodcastMainBundle:Organization.yml',
        ), $manager);
    }
    
}

?>