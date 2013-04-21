<?php

namespace Podcast\MainBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class CategoryController extends FOSRestController
{
    /**
     * "get_category" [GET] /categories/{$slug}
     * 
     * Get a category!
     * @param string $slug
     * @return FOS\RestBundle\View\View
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getCategoryAction($slug)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $category = $em->getRepository('PodcastMainBundle:Category')->findOneBySlug($slug);
        
        if(!$category) {
            throw $this->createNotFoundException('Category does not exist');
        }
        
        $view = $this->view($category, 200)
                     ->setTemplate('PodcastMainBundle:Category:show.html.twig')
                     ->setTemplateVar('entity');

        return $this->handleView($view);
    }
    
    /**
     * "get_categories" [GET] /categories
     * 
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of categories.")
     * @return FOS\RestBundle\View\View
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getCategoriesAction(ParamFetcher $paramFetcher)
    {

        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PodcastMainBundle:Category')->findAll();

        //Paginate
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $paramFetcher->get('page'), 10
        );

        $view = $this->view($pagination)
                ->setTemplateVar('entities');

        return $this->handleView($view);
    }
    
}
