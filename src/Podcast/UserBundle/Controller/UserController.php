<?php

namespace Podcast\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use FOS\RestBundle\Request\ParamFetcher;

class UserController extends FOSRestController
{
    
    public function getListenedAction()
    {
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->view('Please log in', 401);
        }
        $user = $this->get('security.context')->getToken()->getUser();
        return $this->view($user->getListenedTo(), 200);
    }
    
    public function getSubscribedAction()
    {
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->view('Please log in', 401);
        }
        $user = $this->get('security.context')->getToken()->getUser();
        return $this->view($user->getSubscriptions(), 200);
    }
}
