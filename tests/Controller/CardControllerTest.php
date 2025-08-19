<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CardControllerTest extends WebTestCase
{
    public function testCardLanding(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card');
        $this->assertResponseIsSuccessful();
    }

    public function testCardDeckSort(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');
        $this->assertResponseIsSuccessful();
    }

    public function testCardDeckShuffle(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');
        $client->request('GET', '/card/deck/shuffle');
        $this->assertResponseIsSuccessful();
    }

    public function testCardDeckDrawOne(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');
        $client->request('GET', '/card/deck/draw');
        $this->assertResponseIsSuccessful();
    }

    public function testCardDeckDrawNumValid(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');
        $client->request('GET', '/card/deck/draw/3');
        $this->assertResponseIsSuccessful();
    }

    public function testCardDeckDrawNumInvalid(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');
        $client->request('GET', '/card/deck/draw/0');
        $this->assertTrue($client->getResponse()->isRedirection());
    }

    public function testCardDeckReset(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/reset');
        $this->assertTrue($client->getResponse()->isRedirection());
    }
}


