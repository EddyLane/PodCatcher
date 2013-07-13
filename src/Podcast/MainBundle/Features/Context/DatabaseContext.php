<?php
/**
 * Created by JetBrains PhpStorm.
 * User: edwardlane
 * Date: 13/07/2013
 * Time: 13:29
 * To change this template use File | Settings | File Templates.
 */

namespace Podcast\MainBundle\Features\Context;

use Doctrine\Common\DataFixtures\Loader,
    Doctrine\Common\DataFixtures\Executor\ORMExecutor,
    Doctrine\Common\DataFixtures\Purger\ORMPurger,
    Doctrine\ORM\Query;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use FOS\UserBundle\Command\CreateUserCommand;
use Doctrine\ORM\EntityManager;
/**
 * Feature context.
 */
class DatabaseContext extends BehatContext
{
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }
    /**
     * @Given /^the following users exist:$/
     */
    public function theFollowingUsersExist(TableNode $users)
    {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();
        $conn->executeQuery('DELETE FROM user_episode');
        $conn->executeQuery('DELETE FROM user_podcast');
        $em->createQuery('DELETE PodcastUserBundle:User')->execute();
        $em->flush();

        $application = new Application($this->getMainContext()->getKernel());
        $application->add(new CreateUserCommand());
        $command = $application->find('fos:user:create');
        $this->tester = new CommandTester($command);

        foreach($users->getHash() as $user) {

            $result = $this->tester->execute(array(
                'command' => $command->getName(),
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => $user['password']
            ));

            if($result !== 0) {
                throw new \Exception(sprintf("\"%s\" returned an error: (%s)", $cmdName, $this->tester->getDisplay()));
            }
        }
    }
    /**
     * Gets an entity manager
     *
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getMainContext()->getKernel()->getContainer()->get('doctrine')->getManager();
    }
}