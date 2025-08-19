<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SessionControllerTest extends WebTestCase
{
    public function testSessionViewAndDelete(): void
    {
        $client = static::createClient();
        $client->request('GET', '/session');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/session/delete');
        $this->assertTrue($client->getResponse()->isRedirection());
    }
}


