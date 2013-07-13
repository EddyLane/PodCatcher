<?php

namespace Podcast\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() 
    {
        return $this->render('PodcastMainBundle:Default:index.html.twig');
    }

}
