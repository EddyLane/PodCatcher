<?php

namespace Podcast\MainBundle\Controller;

use Podcast\MainBundle\Entity;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class PodcastEpisodeController extends FOSRestController {

    /**
     * "get_podcast_episodes" [GET] /podcasts/{$slug}/episodes
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of podcasts.")
     */
    public function getEpisodesAction($slug, ParamFetcher $paramFetcher) 
    {
        $em = $this->getDoctrine()->getManager();

        $podcast = $em->getRepository('PodcastMainBundle:Podcast')->findOneBySlug($slug);

        if (!$podcast) {
            throw $this->createNotFoundException('This podcast does not exist');
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $podcast->getEpisodes(), $paramFetcher->get('page'), 10
        );
        
        $view = $this->view($podcast->getEpisodes(), 200)
                ->setTemplate('PodcastMainBundle:Default:index.html.twig')
                ->setTemplateVar('entities');

        $view->setHeader('X-Pagination-Total', $pagination->getTotalItemCount());
        $view->setHeader('X-Pagination-Amount', 10);

        return $this->handleView($view);
    }


}

?>
