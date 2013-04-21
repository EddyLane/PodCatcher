<?php

namespace Podcast\MainBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class OrganizationController extends FOSRestController
{
    /**
     * "get_organizations" [GET] /organizations
     * 
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of organizations.")
     * @return FOS\RestBundle\View\View
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getOrganizationsAction(ParamFetcher $paramFetcher)
    {
        
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PodcastMainBundle:Organization')->findAll();

        //Paginate
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $paramFetcher->get('page'), 10
        );

        $view = $this->view($pagination, 200)
                ->setTemplateVar('entities');

        return $this->handleView($view);
    }
    
}
