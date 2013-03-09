<?php

namespace Podcast\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class PodcastsController extends FOSRestController {

    // "options_Podcasts" [OPTIONS] /podcasts
    public function getPodcastsAction() {

        $em = $this->getDoctrine()->getEntityManager();

        $qb = $em->createQueryBuilder();

        $qb->select('p')
                ->from('Podcast\MainBundle\Entity\Podcast', 'p')
                ->orderBy('p.id', 'desc');
        $podcasts = $qb->getQuery()->getArrayResult();
        
        $view = $this->view($podcasts);
        
        return $this->handleView($view);

    }

    // "get_podcasts"     [GET] /podcasts/new/asfasfa

    public function newPodcastsAction() {
        
        $podcast = new Entity\Podcast();
        
        $form = $this->createFormBuilder($podcast)
            ->add('link', 'text')
            ->getForm();
        
        return $this->render('PodcastMainBundle:Default:podcastForm.html.twig', array(
            'form' => $form->createView()
        ));
    }

  
    // "patch_Podcasts"   [PATCH] /podcasts

    public function getPodcastAction($slug) {

        $em = $this->getDoctrine()->getEntityManager();

        //$podcast = $em->getRepository('PodcastMainBundle:Podcast')->findOneBySlug($slug);
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        $qb = $em->createQueryBuilder();

        $qb->select(array("p","e","l"))
                ->from('Podcast\MainBundle\Entity\Podcast', 'p')
                ->where('p.slug = :slug')
                ->leftJoin('p.episodes','e')
                ->leftJoin('e.listenedBy','l','WITH','l.id = :user_id')
                ->orderBy('p.id', 'desc')
                ->setParameter('slug', $slug)
                ->setParameter('user_id', $user->getId());
        
        $podcast = $qb->getQuery()->getArrayResult();
        $view = View::create();
        $view->setData($podcast[0]);
        return $view;
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
