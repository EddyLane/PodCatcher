<?php

namespace Podcast\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class PodcastsController extends Controller {

    public function optionsPodcastsAction() {
        
    }

    // "options_Podcasts" [OPTIONS] /podcasts
    public function getPodcastsAction() {
        

        $em = $this->getDoctrine()->getEntityManager();

        $qb = $em->createQueryBuilder();

        $qb->select('p')
                ->from('Podcast\MainBundle\Entity\Podcast', 'p')
                ->orderBy('p.id', 'desc');

        $podcasts = $qb->getQuery()->getArrayResult();

        $view = View::create()->setData($podcasts);

        return $view;
    }

    // "get_podcasts"     [GET] /podcasts/new/asfasfa

    public function newPodcastsAction() {
        $podcast = new Entity\Podcast();
        $form = $this->createFormBuilder($podcast)
            ->add('link', 'text')
            ->getForm();
        return $this->render('PodcastMainBundle:Default:podcastForm.html.twig', array(
            'form' => $form->createView()
        ));
    }

    // "new_Podcasts"     [GET] /podcasts/new

    public function postPodcastsAction() {
        
    }
    
    

    // "post_Podcasts"    [POST] /podcasts

    public function patchPodcastsAction() {
        
    }

    // "patch_Podcasts"   [PATCH] /podcasts

    public function getPodcastAction($slug) {

        $em = $this->getDoctrine()->getEntityManager();

        //$podcast = $em->getRepository('PodcastMainBundle:Podcast')->findOneBySlug($slug);
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        $qb = $em->createQueryBuilder();

        $qb->select(array("p","e","l"))
                ->from('Podcast\MainBundle\Entity\Podcast', 'p')
                ->where('p.slug = :slug')
                ->leftJoin('p.episodes','e')
                ->leftJoin('e.listenedBy','l','WITH','l.id = :user_id')
                ->orderBy('p.id', 'desc')
                ->setParameter('slug', $slug)
                ->setParameter('user_id', $user->getId());
        


        $podcast = $qb->getQuery()->getArrayResult();
        
        
        $view = View::create();

        $view->setData($podcast[0]);

        return $view;
    }
    
    public function unsubscribePodcastAction($slug) {

        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $podcast = $em->getRepository('PodcastMainBundle:Podcast')->findOneBySlug($slug);

        $user->removeSubscription($podcast);

        $em->persist($user);
        $em->flush();

        $view = View::create();

        $view->setData($podcast);

        return $view;
    }
    
    // "get_Podcast"      [GET] /podcasts/{slug}
    public function subscribePodcastAction($slug) {


        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $podcast = $em->getRepository('PodcastMainBundle:Podcast')->findOneBySlug($slug);

        $user->addSubscription($podcast);

        $em->persist($user);
        $em->flush();

        $view = View::create();

        $view->setData($podcast);

        return $view;
    }
    // "subscribe_Podcast"  [POST]  /podcasts/{slug}/subscribe
    

    public function editPodcastAction($id) {

    }

    // "edit_Podcast"     [GET] /podcasts/{slug}/edit

    public function putPodcastAction($id) {

    }

    // "put_Podcast"      [PUT] /podcasts/{slug}

    public function patchPodcastAction($slug) {
        
    }

    // "patch_Podcast"    [PATCH] /podcasts/{slug}

    public function lockPodcastAction($slug) {
        
    }

    // "lock_Podcast"     [PATCH] /podcasts/{slug}/lock

    public function banPodcastAction($slug, $id) {
        
    }

// "ban_Podcast"      [PATCH] /podcasts/{slug}/ban

    public function removePodcastAction($slug) {
        
    }

// "remove_Podcast"   [GET] /podcasts/{slug}/remove

    public function deletePodcastAction($id) {
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $podcast = $em->find('PodcastMainBundle:Podcast', $id);

        $em->remove($podcast);
       
        $em->flush();
        
        $view = View::create();

        $view->setData($podcast);

        return $view;        
    }

// "delete_Podcast"   [DELETE] /podcasts/{slug}

    public function getPodcastEpisodesAction($slug) {
        
    }

// "get_Podcast_Episodes"    [GET] /podcasts/{slug}/Episodes

    public function newPodcastEpisodesAction($slug) {
        
    }

// "new_Podcast_Episodes"    [GET] /podcasts/{slug}/Episodes/new

    public function postPodcastEpisodesAction($slug) {
        
    }

// "post_Podcast_Episodes"   [POST] /podcasts/{slug}/Episodes

    public function getPodcastEpisodeAction($slug, $id) {
        
    }

// "get_Podcast_Episode"     [GET] /podcasts/{slug}/Episodes/{id}

    public function editPodcastEpisodeAction($slug, $id) {
        
    }

// "edit_Podcast_Episode"    [GET] /podcasts/{slug}/Episodes/{id}/edit

    public function putPodcastEpisodeAction($slug, $id) {
        
    }

// "put_Podcast_Episode"     [PUT] /podcasts/{slug}/Episodes/{id}

    public function postPodcastEpisodeVoteAction($slug, $id) {
        
    }

// "post_Podcast_Episode_vote" [POST] /podcasts/{slug}/Episodes/{id}/vote

    public function removePodcastEpisodeAction($slug, $id) {
        
    }

// "remove_Podcast_Episode"  [GET] /podcasts/{slug}/Episodes/{id}/remove

    public function deletePodcastEpisodeAction($slug, $id) {
        
    }

// "delete_Podcast_Episode"  [DELETE] /podcasts/{slug}/Episodes/{id}
}
