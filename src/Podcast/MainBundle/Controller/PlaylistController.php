<?php

namespace Podcast\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Podcast\MainBundle\Entity\Playlist;
use Podcast\MainBundle\Form\PlaylistType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer as CustomNormalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

/**
 * Playlist controller.
 *
 */
class PlaylistController extends Controller {

    public function renameAction(Request $request) {

        $security_context = $this->get('security.context');

        if (true === $security_context->isGranted('ROLE_USER')) {

            $em = $this->getDoctrine()->getEntityManager();

            $data = json_decode($this->get("request")->getContent(), true);

            $playlist = $em->getRepository('PodcastMainBundle:Playlist')->find($data['id']);

            $user = $security_context->getToken()->getUser();

            if ((int)$playlist->getUserId() !== (int)$user->getId()) {
                $data = array("error" => "Not yours pal.");
            } else {
                $playlist->setName($data['value']);

                $em->persist($playlist);

                $em->flush();
            }
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function addPodcastAction(Request $request) {

        if ($request->getMethod() == 'POST') {
            if (0 === strpos($this->getRequest()->headers->get('Content-Type'), 'application/json')) {
                $data = json_decode($this->getRequest()->getContent(), true);
                $this->getRequest()->request->replace(is_array($data) ? $data : array());
            }
        }

        $em = $this->getDoctrine()->getEntityManager();
        $episode = $em->getRepository('PodcastMainBundle:Episode')->find($data["id"]);
        $playlist = $em->getRepository('PodcastMainBundle:Playlist')->find($data["playlist_id"]);


        $playlist->addEpisode($episode);

        $em->persist($playlist);
        $em->flush();


        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Lists all Playlist entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $security_context = $this->get('security.context');
        // $entities = $em->getRepository('PodcastMainBundle:Playlist')->findAll();

        $serializer = new Serializer(new Serializer\Normalizer\CustomNormalizer());
        $serializer->setEncoder('json', new Serializer\Encoder\JsonEncoder());

        if (true === $security_context->isGranted('ROLE_USER')) {

            $user = $security_context->getToken()->getUser();
            $playlists = $user->getPlaylists();
            return $serializer->encode($playlists, 'json');
        } else {

            $playlists = false;
        }

        return $this->render('PodcastMainBundle:Playlist:index.html.twig', array(
                    'entities' => $playlists
                ));
    }

    /**
     * Finds and displays a Playlist entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PodcastMainBundle:Playlist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Playlist entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PodcastMainBundle:Playlist:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Displays a form to create a new Playlist entity.
     *
     */
    public function newAction() {

        $entity = new Playlist();
        $form = $this->createForm(new PlaylistType(), $entity);

        return $this->render('PodcastMainBundle:Playlist:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    public function blindCreateAction(Request $request) {

        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT COUNT(p.id) FROM Podcast\MainBundle\Entity\Playlist p WHERE p.user_id = ' . $user->getId());
        $count = $query->getSingleScalarResult();

        $entity = new Playlist();
        $entity->setUser($user);
        $entity->setName("Untitled Playlist (" . $count . ")");
        $em->persist($entity);
        $em->flush();


        $json_data = array();
        $json_data["id"] = $entity->getId();
        $json_data["name"] = $entity->getName();
        $json_data["slug"] = $entity->getSlug();
        $json_data["rating"] = $entity->getRating();
//        
        $response = new Response(json_encode(array($json_data)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Creates a new Playlist entity.
     *
     */
    public function createAction(Request $request) {


        $entity = new Playlist();
        $entity->setUser($this->get('security.context')->getToken()->getUser());

        $request = $this->getRequest();
        $form = $this->createForm(new PlaylistType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                $json_data = array();
                $json_data["id"] = $entity->getId();
                $json_data["name"] = $entity->getName();
                $json_data["slug"] = $entity->getSlug();
                $json_data["rating"] = $entity->getRating();
                $response = new Response(json_encode(array($json_data)));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }

            //return $this->redirect($this->generateUrl('playlist_show', array('id' => $entity->getId())));
        }

        return $this->render('PodcastMainBundle:Playlist:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Playlist entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PodcastMainBundle:Playlist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Playlist entity.');
        }

        $editForm = $this->createForm(new PlaylistType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PodcastMainBundle:Playlist:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing Playlist entity.
     *
     */
    public function updateAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PodcastMainBundle:Playlist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Playlist entity.');
        }

        $editForm = $this->createForm(new PlaylistType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('playlist_edit', array('id' => $id)));
        }

        return $this->render('PodcastMainBundle:Playlist:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a Playlist episode.
     *
     */
    public function removeEpisodeAction($episode_id, $playlist_id) {

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            $em = $this->getDoctrine()->getEntityManager();
            $user = $this->get('security.context')->getToken()->getUser();
            $playlist = $em->getRepository('PodcastMainBundle:Playlist')->find($playlist_id);

            if ((int) $playlist->getUserId() !== (int) $user->getId()) {
                return new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }


            $episode = $em->getRepository('PodcastMainBundle:Episode')->find($episode_id);
            $playlist->removeEpisode($episode);

            $em->flush();

            $qb = $em->createQueryBuilder();
            $qb->select('e')
                    ->from('Podcast\MainBundle\Entity\Episode', 'e')
                    ->innerJoin('e.playlists', 'p', 'WITH', 'p.id = :playlist_id')
                    ->orderBy('e.id')
                    ->setParameter('playlist_id', $playlist->getId());

            $query = $qb->getQuery();
            $json_episodes = $query->getArrayResult();
            $response = new Response(json_encode($json_episodes));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    private function returnPlaylistsAction() {
        
    }

    /**
     * Deletes a Playlist episode.
     *
     */
    public function deleteAction($id) {

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            $em = $this->getDoctrine()->getEntityManager();
            $user = $this->get('security.context')->getToken()->getUser();
            $playlist = $em->getRepository('PodcastMainBundle:Playlist')->find($id);

            if ((int) $playlist->getUserId() !== (int) $user->getId())
                die;


            $playlist = $em->getRepository('PodcastMainBundle:Playlist')->find($id);
            $em->remove($playlist);
            $em->flush();

            return $this->forward('PodcastMainBundle:Default:playlists');
        }
    }

    /**
     * Deletes a Playlist entity.
     *
     */
//    public function deleteAction($id) {
//        $form = $this->createDeleteForm($id);
//        $request = $this->getRequest();
//
//        $form->bindRequest($request);
//
//        if ($form->isValid()) {
//            $em = $this->getDoctrine()->getEntityManager();
//            $entity = $em->getRepository('PodcastMainBundle:Playlist')->find($id);
//
//            if (!$entity) {
//                throw $this->createNotFoundException('Unable to find Playlist entity.');
//            }
//
//            $em->remove($entity);
//            $em->flush();
//        }
//         $response = new Response(1);
//        $response->headers->set('Content-Type', 'application/json');
//        return $response;
//        //return $this->redirect($this->generateUrl('playlist'));
//    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
