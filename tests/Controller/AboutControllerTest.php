<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AboutControllerTest extends WebTestCase
{
    public function testAboutRoute(): void
    {
        $client = static::createClient();
        $client->request('GET', '/about');
        $this->assertResponseIsSuccessful();
    }
}


