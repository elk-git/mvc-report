<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MetricsControllerTest extends WebTestCase
{
    public function testMetricsRoute(): void
    {
        $client = static::createClient();
        $client->request('GET', '/metrics');
        $this->assertResponseIsSuccessful();
    }
}


