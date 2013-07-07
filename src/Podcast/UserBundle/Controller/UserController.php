<?php

namespace Podcast\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends FOSRestController
{
    /**
     * Current user.
     *
     * @var User
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    public function initialize(Request $request, SecurityContextInterface $security_context)
    {
        $this->user = $security_context->getToken()->getUser();
        if(!$security_context->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new HttpException(403, 'User not logged in');
        }
    }

    
    public function getListenedAction() 
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $view = $this->view('Please log in', 401);
        } else {
            $user = $this->get('security.context')->getToken()->getUser();
            $episodeIds = $this->getDoctrine()->getManager()->getRepository('PodcastMainBundle:Episode')->getListenedToIds($user);
            $view = $this->view($episodeIds, 200);
        }
        $view->setTemplate('PodcastMainBundle:Default:index.html.twig')
             ->setTemplateVar('entity');
        
        return $this->handleView($view);
    }

    public function postSubscribeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $podcast = $em->getRepository('PodcastMainBundle:Podcast')->find($id);
        
        if(!$podcast) {
            throw $this->createNotFoundException();
        }

        $this->user->addSubscription($podcast);
        $em->persist($this->user);
        $em->flush();

        $view = $this->view(200);
        return $this->handleView($view);
    }

    public function deleteUnsubscribeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $podcast = $em->getRepository('PodcastMainBundle:Podcast')->find($id);

        if(!$podcast) {
            throw $this->createNotFoundException();
        }

        $this->user->removeSubscription($podcast);
        $em->persist($this->user);
        $em->flush();

        $view = $this->view(200);
        return $this->handleView($view);
    }

    public function getUserAction()
    {
        $view = $this->view($this->user, 200);
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
