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
     * @QueryParam(array=true, name="categories", requirements="[a-z]+", description="Categories to filter on")
     * @QueryParam(array=true, name="organizations", requirements="[a-z]+", description="Organizations to filter on")
     * @param ParamFetcher $paramFetcher
     */
    public function getPodcastsAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        
        $query = $em
                ->getRepository('PodcastMainBundle:Podcast')
                ->findAllByCategoryAndOrganization(
                        $paramFetcher->get('categories'), 
                        $paramFetcher->get('organizations')
                )
        ;
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $paramFetcher->get('page'), 20
        );

        $view = $this->view($pagination)
                ->setTemplateVar('entities');

        return $this->handleView($view);
    }
    
}
