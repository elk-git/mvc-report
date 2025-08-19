<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuoteControllerJsonTest extends WebTestCase
{
    public function testQuoteApi(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/quote');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertJson($client->getResponse()->getContent());
    }
}


