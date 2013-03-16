<?php

namespace Podcast\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
                ->setName('podcasts:refresh')
                ->setDescription('Wipe and add episodes again');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>" . $this->getName() . " Task Starting</info>");

        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $podcasts = $em->getRepository("PodcastMainBundle:Podcast")->findAll();

        $feed = new \SimplePie();

        $feed->enable_cache(false);

        foreach ($podcasts as $podcast) {

            try {
                $podcast->refreshEpisodes($feed);
                $em->persist($podcast);
                $em->flush();
            } catch (Exception $e) {
                echo "Duplicate podcast ignored \n\n";
            }
        }

        $output->writeln("<info>" . $this->getName() . " Task Finished</info>");
    }

}
