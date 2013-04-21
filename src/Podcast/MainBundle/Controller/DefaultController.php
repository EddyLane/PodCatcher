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

class DefaultController extends Controller {

    public function newPodcastFormAction() {
        $podcast = new Entity\Podcast();
        $form = $this->createFormBuilder($podcast)
                ->add('link', 'text')
                ->getForm();

        return $this->render('PodcastMainBundle:Default:podcastForm.html.twig', array(
                    'form' => $form->createView()
        ));
    }
   
    

    public function indexAction(Request $request) {

        return $this->render('PodcastMainBundle:Default:index.html.twig');
    }

    protected function processForm(Request $request, $form) {
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

    public function newAction(Request $request) {
        $podcast = new Entity\Podcast();
        $form = $this->createFormBuilder($podcast)
                ->add('link', 'text')
                ->add('name', 'text')
                ->getForm();

        if ($request->getMethod() == 'POST') {
            $success = $this->processForm($request, $form);
        }

        return $this->render('PodcastMainBundle:Default:new.html.twig', array(
                    'form' => $form->createView()
        ));
    }

}
