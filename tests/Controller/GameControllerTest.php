<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function testGameLandingAndDoc(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game');
        $this->assertResponseIsSuccessful();
        $client->request('GET', '/game/doc');
        $this->assertResponseIsSuccessful();
    }

    public function testGameLifecycle(): void
    {
        $client = static::createClient();
        // Start a game
        $client->request('GET', '/game/start');
        $this->assertResponseIsSuccessful();

        // Hit
        $client->request('GET', '/game/hit');
        $this->assertResponseIsSuccessful();

        // Stand
        $client->request('GET', '/game/stand');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/api/game');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        // Reset
        $client->request('GET', '/game/reset');
        $this->assertTrue($client->getResponse()->isRedirection());
    }
}


