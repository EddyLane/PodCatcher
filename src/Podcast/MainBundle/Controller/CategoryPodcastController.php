<?php

namespace Podcast\MainBundle\Controller;

use Podcast\MainBundle\Form\PodcastType;
use Podcast\MainBundle\Entity;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class CategoryPodcastController extends FOSRestController
{
    /**
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of podcasts.")
     * @param type $categorySlug
     * @return FOS\RestBundle\View\View
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getPodcastsAction($slug, ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('PodcastMainBundle:Category')->findOneBySlug($slug);
                
        if(!$category) {
            throw $this->createNotFoundException('This category does not exist');
        }
        
        //Get all podcasts from the category
        $query = $em->getRepository('PodcastMainBundle:Podcast')->findByCategory($category);

        //Paginate
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $paramFetcher->get('page'), 10
        );

        $view = $this->view($pagination, 200)
                ->setTemplate('PodcastMainBundle:Podcast:getPodcasts.html.twig')
                ->setTemplateVar('entities');

        return $this->handleView($view);
    }
}
