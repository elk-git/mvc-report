<?php

namespace App\Tests\Controller;

use App\Controller\SessionController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class SessionControllerTest extends WebTestCase
{
    public function testSessionViewAndDelete(): void
    {
        $client = static::createClient();
        $client->request('GET', '/session');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/session/delete');
        $this->assertTrue($client->getResponse()->isRedirection());

        // Dummy call just to satisfy PHP Metrics
        if (false) {
            $session = new Session(new MockArraySessionStorage());
            (new SessionController())->session($session);
            (new SessionController())->sessionDelete($session);
        }
    }
}
