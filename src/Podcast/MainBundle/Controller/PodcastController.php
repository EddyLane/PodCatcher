<?php

namespace Podcast\MainBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;


class PodcastController extends FOSRestController
{
    /**
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     * @QueryParam(name="amount", requirements="\d+", default="16", description="Amount of podcasts")
     * @QueryParam(name="sort", requirements="[a-z]+", description="Search")
     * @QueryParam(name="direction", requirements="[a-z]+", default="desc", description="Direction to sort")
     * @QueryParam(name="search", requirements="[a-z]+", description="Search")
     * @QueryParam(array=true, name="categories", requirements="[a-z]+", description="Categories to filter on")
     * @QueryParam(array=true, name="organizations", requirements="[a-z]+", description="Organizations to filter on")
     * @param ParamFetcher $paramFetcher
     */
    public function getPodcastsAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
                
        $podcasts = $em
                ->getRepository('PodcastMainBundle:Podcast')
                ->findAllByCategoryAndOrganization (
                    $this->get('security.context')->getToken(),
                    $paramFetcher->get('categories'), 
                    $paramFetcher->get('organizations'),
                    $paramFetcher->get('sort'),
                    $paramFetcher->get('direction'),
                    $paramFetcher->get('amount'),
                    $paramFetcher->get('page'),
                    $paramFetcher->get('search')
                )
        ;
                
        $view = $this->view($podcasts['entities'])
                     ->setTemplate('PodcastMainBundle:Podcast:getPodcasts.html.twig')
                     ->setTemplateVar('entities');

        $view->setHeaders($podcasts['metadata']);

        return $this->handleView($view);
    }
    
    /**
     * "get_podcast" [GET] /podcasts/{$slug}
     * 
     * Get a podcast!
     * @param string $slug
     * @return FOS\RestBundle\View\View
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getPodcastAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $podcast = $em->getRepository('PodcastMainBundle:Podcast')->findOneBySlug($slug);
        
        if(!$podcast) {
            throw $this->createNotFoundException();
        }
        
        $view = $this->view($podcast, 200)
                     ->setTemplate('PodcastMainBundle:Podcast:getPodcast.html.twig')
                     ->setTemplateVar('entity');

        return $this->handleView($view);
    }
}
