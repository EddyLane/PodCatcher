<?php

namespace Podcast\MainBundle\Controller;

use Podcast\MainBundle\Entity;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;

/**
 * Description of PodcastEpisodeController
 *
 * @author eddy
 */
class PodcastEpisodeController extends FOSRestController {

    /**
     * "get_podcast_episodes" [GET] /podcasts/{$slug}/episodes
     * 
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of podcasts.")
     * @return FOS\RestBundle\View\View
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getEpisodesAction($slug, ParamFetcher $paramFetcher) 
    {
        $em = $this->getDoctrine()->getManager();

        $podcast = $em->getRepository('PodcastMainBundle:Podcast')->findOneBySlug($slug);

        if (!$podcast) {
            throw $this->createNotFoundException('This podcast does not exist');
        }
        //Paginate
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $podcast->getEpisodes(), $paramFetcher->get('page'), 10
        );
        
        $pagination['metadata'] = [
            'total' => $pagination->getTotalItemCount()
        ];
        
        $view = $this->view($pagination, 200)
                ->setTemplate('PodcastMainBundle:Default:index.html.twig')
                ->setTemplateVar('entities');

        return $this->handleView($view);
    }
    
    /**
     * "listen_podcast_episode" [GET] /podcasts/{$slug}/episodes/{$id}/listen
     * 
     * @return FOS\RestBundle\View\View
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */    
    public function listenEpisodeAction($slug, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->get('security.context')->getToken()->getUser();
        
        $episode = $em->getRepository('PodcastMainBundle:Episode')->find($id);

        $user->addListenedTo($episode);

        $em->persist($user);
        $em->flush();
        
        return $this->view('success', 200);
    }

}

?>
