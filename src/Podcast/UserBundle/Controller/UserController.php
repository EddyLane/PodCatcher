<?php

namespace Podcast\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use FOS\RestBundle\Request\ParamFetcher;

class UserController extends FOSRestController
{
    
    public function getListenedAction() 
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $view = $this->view('Please log in', 401);
        } else {
            $user = $this->get('security.context')->getToken()->getUser();
            $view = $this->view($user->getListenedTo(), 200);
        }
        $view->setTemplate('PodcastMainBundle:Default:index.html.twig')
             ->setTemplateVar('entity');
        
        return $this->handleView($view);
    }
    
    
    /**
     * "post_listen_podcast_episode" [POST] /podcasts/{$slug}/episodes/{$id}/listen
     * 
     * @return FOS\RestBundle\View\View
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */    
    public function postListenAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->get('security.context')->getToken()->getUser();
        
        $episode = $em->getRepository('PodcastMainBundle:Episode')->find($id);

        $user->addListenedTo($episode);

        $em->persist($user);
        $em->flush();
        
        return $this->view('success', 200);
    }
    
    public function getSubscribedAction()
    {
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $view =  $this->view('Please log in', 401);
        }
        else {
            $user = $this->get('security.context')->getToken()->getUser();
            $view = $this->view($user->getSubscriptions(), 200);
        }

        $view->setTemplate('PodcastMainBundle:Default:index.html.twig')
             ->setTemplateVar('entity');
        
        return $this->handleView($view);
    }

    public function getSubscriptionAction($slug)
    {
        $response = $this->forward('PodcastMainBundle:PodcastEpisode:getEpisodes', array(
            'slug'  => $slug
        ));

        return $response;
    }
}
