<?php

namespace spec\Podcast\MainBundle\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NewRefreshCommandSpec extends ObjectBehavior
{

   function it_should_be_initializable()
    {
        $this->shouldHaveType('Podcast\MainBundle\Command\NewRefreshCommand');
        $this->shouldBeAnInstanceOf('Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand');
    }

    /**
     * @param  Symfony\Component\DependencyInjection\Container $container
     * @param  Symfony\Component\HttpKernel\Log\LoggerInterface $logger
     * @param  Symfony\Component\Console\Input\InputInterface $input
     * @param  Symfony\Component\Console\Output\OutputInterface $output
     * @param  Podcast\MainBundle\Update\Runner $runner
     */
    function let($container, $logger, $input, $output, $runner)
    {
        $container->get('logger')->willReturn($logger);

        $runner->execute()->willReturn(true);
        $container->get('podcast_mainbundle.update.runner')->willReturn($runner);

        $this->setContainer($container);
    }

    function it_should_output_a_message_to_say_that_it_is_commencing_the_task($input, $output, $runner)
    {
    	$output->writeln("<info>Refresh Task Starting</info>")->shouldBeCalled();
    	$output->writeln("<info>Refresh Task Done</info>")->shouldBeCalled();
    	$this->execute($input, $output);
    }



}
