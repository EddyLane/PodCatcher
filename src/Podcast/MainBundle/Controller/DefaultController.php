<?php

namespace Podcast\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Podcast\MainBundle\Entity as Entity;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\EnCoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Podcast\MainBundle\Entity\Episode;

class DefaultController extends Controller
{
//    private function getJsonSerializer() {
//        $serializer = new Serializer(
//                        array(
//                            new GetSetMethodNormalizer()
//                        ), array(
//                    'json' => new JsonEncoder()
//                        )
//        );
//        return $serializer;
//    }
//

    public function newPodcastFormAction()
    {
        $podcast = new Entity\Podcast();
        $form = $this->createFormBuilder($podcast)
                ->add('link', 'text')
                ->getForm();

        return $this->render('PodcastMainBundle:Default:podcastForm.html.twig', array(
                    'form' => $form->createView()
                ));
    }
//
//    public function addSubscriptionAction(Request $request) {
//
//        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
//
//            if (($request->getMethod() == 'POST') && (0 == strpos($this->getRequest()->headers->get('Content-Type'), 'application/json'))) {
//
//                $em = $this->getDoctrine()->getEntityManager();
//
//                $user = $this->get('security.context')->getToken()->getUser();
//                $podcast = $em->getRepository('PodcastMainBundle:Podcast')->find($this->getRequest()->request->get('id'));
//
//                $user->addSubscription($podcast);
//
//                $em->persist($user);
//                $em->flush();
//            }
//        }
//
//        $response = $this->forward('PodcastMainBundle:Default:subscriptions', array(
//            'request' => $request
//                ));
//        return $response;
//    }
//
//    public function mostSubscribedAction(Request $request) {
//        $em = $this->getDoctrine()->getEntityManager();
//
//        if ($request->isXmlHttpRequest()) {
//
//
//            $podcasts = $em->getRepository("PodcastMainBundle:Podcast")->getMostSubscribedQuery(10, $this->get('security.context')->getToken()->getUser());
//            $podcast_json = $podcasts->getArrayResult();
//        }
//        $response = new Response(json_encode($podcast_json));
//        $response->headers->set('Content-Type', 'application/json');
//        return $response;
//    }
//
//    public function subscriptionsAction(Request $request) {
//
//
//        $em = $this->getDoctrine()->getEntityManager();
//
//        if ($request->isXmlHttpRequest()) {
//
//            $user = false;
//            if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
//                $user = $this->get('security.context')->getToken()->getUser();
//            }
//
//            $podcasts = $em->getRepository("PodcastMainBundle:Podcast")->getSubscribedPodcasts($user);
//            $serializer = $this->getJsonSerializer();
//            $podcast_json = $serializer->serialize($podcasts, 'json');
//
//
//            $response = new Response($podcast_json);
//            $response->headers->set('Content-Type', 'application/json');
//            return $response;
//        }
//    }
//
//    public function listenedToAction(Request $request, $id) {
//
//        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
//
//            $user = $this->get('security.context')->getToken()->getUser();
//
//            $em = $this->getDoctrine()->getEntityManager();
//            $episode = $em->getRepository('PodcastMainBundle:Episode')->find($id);
//
//            $user->addListenedTo($episode);
//            $em->persist($user);
//            $em->flush();
//
//            $serializer = $this->getJsonSerializer();
//            $user_json = $serializer->serialize($user, 'json');
//
//
//
//            $user_json = json_decode($user_json);
//            unset($user_json->password,$user_json->lastlogin,$user_json->plainpassword,$user_json->roles,$user_json->salt,$user_json->id,$user_json->groups,$user_json->groupnames,$user_json->confirmationtoken,$user_json->passwordrequestdat);
//            $user_json = json_encode($user_json);
//            $response = new Response($user_json);
//            $response->headers->set('Content-Type', 'application/json');
//            return $response;
//        }
//    }
//
//    public function playlistsAction(Request $request) {
//
//        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
//
//            $em = $this->getDoctrine()->getEntityManager();
//            $qb = $em->createQueryBuilder();
//            $user = $this->get('security.context')->getToken()->getUser();
//
//            $qb->select('p', 'e')
//                    ->from('Podcast\MainBundle\Entity\Playlist', 'p')
//                    ->leftJoin('p.episodes', 'e', 'WITH', 'p.user_id = :user_id')
//                    ->orderBy('p.id', 'desc')
//                    ->setParameter('user_id', $user->getId());
//
//            $query = $qb->getQuery();
//            $json_playlists = $query->getArrayResult();
//        } else {
//            $json_playlists = array();
//        }
//
//        $response = new Response(json_encode($json_playlists));
//        $response->headers->set('Content-Type', 'application/json');
//        return $response;
//    }
//
//
//    public function createAction(Request $request) {
//        die("BOB");
//    }
//
//
//    /**
//     *
//     * @param type $name
//     * @return type
//     */
//
//   public function deleteAction(Request $request) {
//       die("ABAGAGE");
//   }
//
//
   public function indexAction(Request $request)
   {
        return $this->render('PodcastMainBundle:Default:index.html.twig');
    }
//
//    /**
//     *
//     * @param Request $request
//     * @param type $id
//     * @return type
//     */
//    public function showAction(Request $request, $id) {
//
//        $podcast = $this->getDoctrine()->getEntityManager()->find('PodcastMainBundle:Podcast', $id);
//        $podcast->init();
//
//        if ($request->isXmlHttpRequest()) {
//
//            $serializer = $this->getJsonSerializer();
//
//            $json = $serializer->serialize($podcast, 'json');
//            $response = new Response($json);
//
//            return $response;
//        }
//
//        return $this->render('PodcastMainBundle:Default:show.html.twig', array(
//                    'podcast' => $podcast
//                ));
//    }
//
//    public function editAction($id) {
//        $em = $this->getDoctrine()->getEntityManager();
//    }

    protected function processForm(Request $request, $form)
    {
        $form->bindRequest($request);

        if ($form->isValid()) {

            $podcast = $form->getData();

            if ($podcast->init()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($podcast);

                $em->flush();

                return $podcast;
            }
        }

        return $form->getErrors();
    }

    public function newAction(Request $request)
    {
        $podcast = new Entity\Podcast();
        $form = $this->createFormBuilder($podcast)
                ->add('link', 'text')
                ->getForm();

        if ($request->getMethod() == 'POST') {
            $success = $this->processForm($request, $form);
            if ($request->isXmlHttpRequest()) {
                $serializer = $this->getJsonSerializer();
                $json = $serializer->serialize($success, 'json');
                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
        }

        return $this->render('PodcastMainBundle:Default:new.html.twig', array(
                    'form' => $form->createView()
                ));
    }

}
