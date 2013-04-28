<?php

namespace Podcast\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Podcast\MainBundle\Entity;
use Doctrine\ORM\EntityManager;

class RefreshCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('podcasts:refresh')
                ->setDescription('Wipe and add episodes again');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("<info>" . $this->getName() . " Task Starting</info>");

        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $podcasts = $em->getRepository("PodcastMainBundle:Podcast")->findAll();

        $loader = new \SimplePie();

        $loader->enable_cache(false);

        foreach ($podcasts as $podcast) {

            $this->refreshPodcast($podcast, $loader, $em, $output);
        }

        $output->writeln("<info>" . $this->getName() . " Task Finished</info>");
    }

    protected function refreshPodcast(Entity\Podcast $podcast, \SimplePie $loader, EntityManager $em, OutputInterface $output) {

        $loader->set_feed_url($podcast->getLink());

        if (!$loader->init()) {
            throw new \Exception("PIE couldnt init", 'item');
        }

        foreach ($loader->get_items() as $episodeXml) {
            
            $enclosure = $episodeXml->get_enclosure();
            $episode = $em->getRepository('PodcastMainBundle:Episode')->findOneByHash($enclosure->link);

            if (!$episode) {

                $output->writeln(sprintf('<info>Podcast <comment>%s</comment> new episode <comment>%s</comment></info>', $podcast->getName(), $episodeXml->get_title()));

                $episode = new Entity\Episode();
                $episode->setLink($enclosure->link);
                $episode->setHash($enclosure->link);
                $episode->setName($episodeXml->get_title());
                $episode->setDescription($episodeXml->get_item_tags(SIMPLEPIE_NAMESPACE_ITUNES, 'summary')[0]["data"]);
                $episode->setLength($episodeXml->get_item_tags(SIMPLEPIE_NAMESPACE_ITUNES, 'duration')[0]["data"]);
                $episode->setPodcast($podcast);

                $em->persist($episode);
            } else {
                $output->writeln(sprintf('<error>Podcast <comment>%s</comment> existing episode <comment>%s</comment></error>', $podcast->getName(), $episodeXml->get_title()));
            }
            $em->flush();
        }
    }

}
