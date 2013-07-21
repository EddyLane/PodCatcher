<?php
/**
 * Created by JetBrains PhpStorm.
 * User: edwardlane
 * Date: 20/07/2013
 * Time: 19:20
 * To change this template use File | Settings | File Templates.
 */

namespace Podcast\MainBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class EpisodeController extends FOSRestController {

    /**
     * @QueryParam(name="pub_date", requirements="\d+-\d+-\d+", description="pub_date")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     * @QueryParam(name="amount", requirements="\d+", default="16", description="Amount of eps")
     * @QueryParam(name="sort", requirements="[a-z]+", description="Search")
     * @QueryParam(name="direction", requirements="[a-z]+", default="desc", description="Direction to sort")
     * @QueryParam(name="podcast", description="Podcasts to filter on")
     * @param ParamFetcher $paramFetcher
     */
    public function getEpisodesAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();

        $episodes = $em
            ->getRepository('PodcastMainBundle:Episode')
            ->getEpisodes (
                $paramFetcher->get('pub_date'),
                preg_split('@,@', $paramFetcher->get('podcast'), NULL, PREG_SPLIT_NO_EMPTY), //Bullshit hack to get around the fact that angular sends arrays up like this &podcast=1,2,3,4 instead of &podcast[]=1&podcast[]=2 etc
                $paramFetcher->get('sort'),
                $paramFetcher->get('direction'),
                $paramFetcher->get('amount'),
                $paramFetcher->get('page')
            )
        ;

        $view = $this->view($episodes['entities'])
                     ->setTemplateVar('entities');

        $view->setHeaders($episodes['metadata']);

        return $this->handleView($view);
    }

}