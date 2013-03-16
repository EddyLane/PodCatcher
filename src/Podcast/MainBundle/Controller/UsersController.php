<?php

namespace Podcast\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View;

class UsersController extends Controller
{
    public function authenticateUsersAction()
    {
        $view = View::create();

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            $user = $this->get('security.context')->getToken()->getUser();
            $response = array('success' => true, 'user' => $user->getPublicData());

            $em = $this->getDoctrine()->getEntityManager();
            $qb = $em->createQueryBuilder();

            $qb->select(array("u", "s"))
                    ->from('Podcast\MainBundle\Entity\User', 'u')
                    ->leftJoin('u.subscriptions', 's')
                    ->where('u.id = :user_id')
                    ->setParameter('user_id', $user->getId());

            $userData = $qb->getQuery()->getArrayResult();
        }

        $view->setData($userData[0]);

        return $view;
    }

}
