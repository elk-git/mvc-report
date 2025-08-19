<?php

namespace App\Tests\Controller;

use App\Controller\AboutController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;


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
        $container = static::getContainer();

        $controller = new AboutController();
        $controller->setContainer($container);

        // Mocka Twig för att undvika manifest.json-problemet
        $twig = $container->get('twig');
        $twig->addFunction(new TwigFunction('encore_entry_link_tags', fn($entry) => ''));
        $twig->addFunction(new TwigFunction('encore_entry_script_tags', fn($entry) => ''));

        $response = $controller->about();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getContent());
    }

}