<?php

namespace spec\Podcast\MainBundle\Update;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RunnerSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\Container $container
     * @param Podcast\MainBundle\Update\Worker $worker
     * @param Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param Doctrine\ORM\EntityManager $manager
     * @param Podcast\MainBundle\Entity\PodcastRepository $repository
     */
    function let($container, $doctrine, $manager, $repository)
    {

        $container->get('doctrine')->willReturn($doctrine);
        $doctrine->getManager()->willReturn($manager);
        $manager->getRepository('PodcastMainBundle:Podcast')->willReturn($repository);
        $this->setContainer($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Podcast\MainBundle\Update\Runner');
    }


    function it_should_add_a_worker_to_the_list_of_workers($worker)
    {
    	$this->addWorker($worker);
    	$this->getWorkers()->shouldHaveCount(1);
    }


    function it_should_retrieve_the_amount_of_podcasts_currently_in_the_system($container, $doctrine, $manager, $repository)
    {
        $repository->getPodcastIds()->shouldBeCalled();
		$this->execute();
    }

    function it_should_divide_the_podcast_list_by_the_amount_of_workers_specified_and_add_that_many_workers($repository)
    {
        $repository
            ->getPodcastIds()
            ->willReturn();

        $this->execute(10);
        $this->getWorkers()->shouldHaveCount(10);
    }



    function it_should_return_true_when_it_has_executed_everything_successfully()
    {
    	$this->execute()->shouldReturn(true);
    }
}
