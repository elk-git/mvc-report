<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LuckyControllersTest extends WebTestCase
{
    public function testLuckyHtml(): void
    {
        $client = static::createClient();
        $client->request('GET', '/lucky');
        $this->assertResponseIsSuccessful();
    }

    public function testLuckyApiJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/lucky');
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertJson($client->getResponse()->getContent());
    }
}


