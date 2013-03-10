<?php

namespace Podcast\MainBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase {

    public function __construct() {
        $this->client = static::createClient();
    }

    protected function getClient() {
        return $this->client;
    }

    public function testCompleteScenario() {
        // Create a new client to browse the application
        $client = $this->getClient();
        // Create a new entry in the database
        $crawler = $client->request('GET', '/category/');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $crawler = $client->click($crawler->selectLink('New')->link());


        //die("<pre>".print_r($client->getResponse()->getContent(),1)."</pre>");
        $name = $this->generateRandomString(20);


        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'podcast_mainbundle_categorytype[name]' => $name
        ));

        $client->submit($form);

        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertTrue($crawler->filter('h1:contains("' . $name . '")')->count() > 0);

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $newName = $this->generateRandomString(20);

        //die("<pre>" . print_r($client->getResponse()->getContent(), 1) . "</pre>");

        $form = $crawler->selectButton('Update')->form(array(
            'podcast_mainbundle_categorytype[name]' => $newName
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertTrue($crawler->filter('h1:contains("' . $newName . '")')->count() > 0);

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/' . $newName . '/', $client->getResponse()->getContent());
    }

    /**
     * Generate a random string of a given length
     * 
     * @param int $length
     * @return string
     */
    protected function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

}