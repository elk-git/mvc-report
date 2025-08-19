<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiDeckControllerTest extends WebTestCase
{
    public function testApiDeckSorted(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testApiDeckShuffle(): void
    {
        $client = static::createClient();
        // Ensure deck exists via GET first
        $client->request('GET', '/api/deck');
        $client->request('POST', '/api/deck/shuffle');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testApiDeckDrawDefaultAndNumber(): void
    {
        $client = static::createClient();
        // Ensure deck exists via GET first
        $client->request('GET', '/api/deck');
        $client->request('POST', '/api/deck/draw');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $client->request('POST', '/api/deck/draw/3');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testApiDeckReset(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck/reset');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }
}


