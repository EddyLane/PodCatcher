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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use FOS\UserBundle\Controller\SecurityController as FOSSecurityController;

/**
 * Security controller.
 * @Route("/")
*/
class DefaultController extends Controller {


    public function indexAction() 
    {
        return $this->render('PodcastMainBundle:Default:index.html.twig');
    }
    /**
     * @Route("/home", name="login")
     * @Method({"POST"})
     */
    public function homeAction(Request $request)
    {
    	
    }
    
}
