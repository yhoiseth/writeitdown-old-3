<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    protected function setUp()
    {
        $this->bootKernel();
        $this->setContainer(static::$kernel->getContainer());
        $this->loadFixtures([]);
    }

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

        $container = $this->getContainer();
        $userManager = $container->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setUsername('marcus');
        $email = 'marcus@aurelius.com';
        $user->setEmail($email);
        $user->setEmailCanonical($email);
        $user->setPlainPassword('equanimity');
        $userManager->updateUser($user);
    }

    /**
     * @param Client $client
     */
    private function assertThatResponseIsOk(Client $client): void
    {
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
