<?php

namespace Podcast\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Podcast\MainBundle\Update\Runner;

class NewRefreshCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this
                ->setName('podcasts:refresh')
                ->setDescription('Wipe and add episodes again');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Refresh Task Starting</info>");
        $this->getContainer()->get('podcast_mainbundle.update.runner')->execute();
        $output->writeln("<info>Refresh Task Done</info>");
    }
}