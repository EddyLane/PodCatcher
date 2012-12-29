<?php

namespace Podcast\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\SecurityExtraBundle\Annotation\Secure;

class EpisodesController extends Controller {

    public function listenEpisodesAction($id) {

        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $episode = $em->getRepository('PodcastMainBundle:Episode')->find($id);

        $user->addListenedTo($episode);

        $em->persist($user);
        $em->flush();
    }

    public function unlistenEpisodesAction($id) {

        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $episode = $em->getRepository('PodcastMainBundle:Episode')->find($id);

        $user->removeListenedTo($episode);

        $em->persist($user);
        $em->flush();
    }

}
