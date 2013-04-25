<?php

namespace Podcast\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Podcast\MainBundle\Entity as Entity;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\EnCoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Podcast\MainBundle\Entity\Episode;

class DefaultController extends Controller {

    public function indexAction() 
    {
        return $this->render('PodcastMainBundle:Default:index.html.twig');
    }
    
}
