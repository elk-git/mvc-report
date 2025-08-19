<?php

namespace App\Tests\Controller;

use App\Controller\AboutController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\AboutController
 */
class AboutControllerTest extends WebTestCase
{
    public function testAboutPageLoads(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/about');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    public function testAboutControllerDirectCall(): void
    {
        self::bootKernel();
        $controller = new AboutController();
        $controller->setContainer(static::getContainer());
        $response = $controller->about();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getContent());
    }

}