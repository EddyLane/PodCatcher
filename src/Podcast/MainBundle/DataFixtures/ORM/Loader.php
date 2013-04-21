<?php

namespace Podcast\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Nelmio\Alice\Fixtures;

abstract class Loader implements FixtureInterface 
{
    const FIXTURE_DIR = '/../';
    
    /**
     * Add a correct full path to feature file names and execute
     * 
     * @param array $fixtures
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    protected function loadFixtures(array $fixtures, ObjectManager $manager) 
    {
        foreach($fixtures as $i => $fixture) {
            $fixtures[$i] = $this->replaceFixtureFilePath($fixture);
        }
        Fixtures::load($fixtures, $manager);
    }
    
    /**
     * Converts a filename to a path with filename
     * 
     * @param type $filename
     * @return string $path
     * @throws FileNotFoundException
     */
    protected function replaceFixtureFilePath($filename) 
    {
        $path = sprintf(__DIR__ . self::FIXTURE_DIR . '/%s', $filename);
        if (!file_exists($path)) {
            throw new FileNotFoundException(sprintf("File '%s' not found (%s)", $filename, $path));
        }
        return $path;
    }
}

?>