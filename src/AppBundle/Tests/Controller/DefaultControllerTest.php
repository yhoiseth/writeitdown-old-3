<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertThatResponseIsOk($client);
        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }

    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertThatResponseIsOk($client);
    }

    /**
     * @param Client $client
     */
    private function assertThatResponseIsOk(Client $client): void
    {
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
