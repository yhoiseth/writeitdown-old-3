<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;

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

        $this->assertContains(
            'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
            $crawler->filter('head')->html()
        );

        $this->assertContains('Documents', $crawler->filter('#container h1')->text());
    }

    public function testLogin()
    {
        $client = $this->makeClient();

        /** @var Crawler $crawler */
        $crawler = $client->request('GET', '/login');

        $this->assertThatResponseIsOk($client);

        $container = $this->getContainer();
        $userManager = $container->get('fos_user.user_manager');

        $username = 'marcus';
        $email = 'marcus@aurelius.com';
        $password = 'equanimity';

        $user = $userManager->createUser();

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setEmailCanonical($email);
        $user->setPlainPassword($password);
        $user->setEnabled(true);

        $userManager->updateUser($user);

        $form = $crawler
            ->selectButton('_submit')
            ->form([
                '_username' => $username,
                '_password' => $password,
            ])
        ;

        $client->submit($form);
        $this->assertStatusCode('302', $client);

        $client->followRedirect();
        $this->assertThatResponseIsOk($client);

        $path = $client->getRequest()->getPathInfo();
        $this->assertNotEquals('/login', $path);
        $this->assertEquals('/', $path);

        $client->request('GET', '/profile/');
        $this->assertContains('Logged in as marcus', $client->getCrawler()->html());
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
