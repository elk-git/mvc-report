<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReportControllerTest extends WebTestCase
{
    public function testReportRoute(): void
    {
        $client = static::createClient();
        $client->request('GET', '/report');
        $this->assertResponseIsSuccessful();
    }
}


