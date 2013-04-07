<?php

namespace Podcast\MainBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class CategoryController extends FOSRestController
{
    /**
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
        
        $view = $this->view($category);

        return $this->handleView($view);
    }
}
