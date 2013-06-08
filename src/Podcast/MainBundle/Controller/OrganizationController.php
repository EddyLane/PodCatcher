<?php

namespace Podcast\MainBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class OrganizationController extends FOSRestController
{
    /**
     * "get_organization" [GET] /organizations/{$slug}
     * 
     * Get an organization
     * 
     * @param string $slug
     * @return FOS\RestBundle\View\View
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getOrganizationAction($slug)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $organization = $em->getRepository('PodcastMainBundle:Organization')->findOneBySlug($slug);
        
        if(!$organization) {
            throw $this->createNotFoundException('Category does not exist');
        }
        
        $view = $this->view($organization, 200)
                     ->setTemplateVar('entity');

        return $this->handleView($view);
    }   
    
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

        $query = $em->getRepository('PodcastMainBundle:Organization')->findBy(array(), array('slug' => 'asc'));

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
