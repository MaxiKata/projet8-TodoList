<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $crawler = $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("Login", $crawler->filter('.container h1')->text());
    }

    public function testIndexRedirection()
    {
        $client = static::createClient();

        $client->request('GET', '/');
        // Test redirection vers la page Login si non connecté
        $this->assertTrue($client->getResponse()->isRedirect());

        // Follow Redirection
        $crawler = $client->followRedirect();

        // Récupération Formulaire et remplissage pour identification
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'User';
        $form['_password'] = 'hihi';
        $client->submit($form);

        // Test de redirection
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        // Test de redirection vers la HomePage
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
