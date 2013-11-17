<?php

namespace Podcast\MainBundle\Update;

use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\ORM\EntityManager;
use Hautelook\GearmanBundle\Service\Gearman;
use Podcast\MainBundle\Update\Worker;

class Runner extends ContainerAware
{
    private $em;
	private $workers = [];
	
    public function addWorker(Worker $worker)
    {
    	$this->workers[] = $worker;
    }

    public function getWorkers()
    {
    	return $this->workers;
    }

    private function getPodcastIds()
    {
        return $this
            ->container
            ->get('doctrine')
            ->getManager()
            ->getRepository('PodcastMainBundle:Podcast')
            ->getPodcastIds()
        ;
    }

    private function createWorkers($workerAmounbt)
    {
        $this->workers = array_fill(0, $workerAmount, new Worker());
    }

    public function execute($workers = 10)
    {
        // $this->createWorkers($workers);
        // $podcastIds = $this->getPodcastIds();
        // return true;
    }
}
