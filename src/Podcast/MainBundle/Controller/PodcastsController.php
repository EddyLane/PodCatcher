<?php

namespace Podcast\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Podcast\MainBundle\Form\PodcastType;
use Podcast\MainBundle\Entity;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class PodcastsController extends FOSRestController {
    

    public function getPodcastsAction() {

        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PodcastMainBundle:Podcast')->findAllWithDefaultSort($this->get('request')->query->get('sort', "id"), $this->get('request')->query->get('direction', "desc"));


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $this->get('request')->query->get('page', 1), 10
        );

        
        $view = $this->view($pagination);
        
        return $this->handleView($view);

    }

    // "get_podcasts"     [GET] /podcasts/new/asfasfa

    public function newPodcastsAction() {
        
        $entity = new Entity\Podcast();
        $form = $this->createForm(new PodcastType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

  
    // "patch_Podcasts"   [PATCH] /podcasts

    public function getPodcastAction($slug) {

        $em = $this->getDoctrine()->getEntityManager();

        $podcast = $em->getRepository('PodcastMainBundle:Podcast')->findOneBySlug($slug);

        $view = $this->view($podcast);

        return $this->handleView($view);
    }

    public function unsubscribePodcastAction($slug) {

        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $podcast = $em->getRepository('PodcastMainBundle:Podcast')->findOneBySlug($slug);

        $user->removeSubscription($podcast);

        $em->persist($user);
        $em->flush();

        $view = View::create();

        $view->setData($podcast);

        return $view;
    }
    
    // "get_Podcast"      [GET] /podcasts/{slug}
    public function subscribePodcastAction($slug) {


        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $podcast = $em->getRepository('PodcastMainBundle:Podcast')->findOneBySlug($slug);

        $user->addSubscription($podcast);

        $em->persist($user);
        $em->flush();

        $view = View::create();

        $view->setData($podcast);

        return $view;
    }
    
}
