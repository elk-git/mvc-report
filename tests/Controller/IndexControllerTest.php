<?php

namespace App\Tests\Controller;

use App\Controller\IndexController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * @SuppressWarnings("PHPMD.TooManyPublicMethods")
 */
class IndexControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testIndexControllerDirectInstantiation()
    {
        // Get the controller from the container to ensure proper dependency injection
        $controller = static::getContainer()->get(IndexController::class);
        
        // Verify the controller is an instance of IndexController
        $this->assertInstanceOf(IndexController::class, $controller);
        
        $response = $controller->index();
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIndexRouteExists()
    {
        $client = static::createClient();
        $router = static::getContainer()->get('router');
        
        // Correct url.
        $url = $router->generate('index');
        $this->assertEquals('/', $url);
    }

    public function testIndexResponseContent()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'text/html; charset=UTF-8');
    }

    public function testIndexControllerMethodExists()
    {
        $controller = new IndexController();
        
        // Callabe
        $this->assertTrue(method_exists($controller, 'index'));
        $this->assertTrue(is_callable([$controller, 'index']));
    }

    public function testIndexControllerClassStructure()
    {
        $this->assertTrue(class_exists(IndexController::class));
        
        $reflection = new \ReflectionClass(IndexController::class);
        $this->assertTrue($reflection->hasMethod('index'));
        
        $method = $reflection->getMethod('index');
        $this->assertTrue($method->isPublic());
        $this->assertEquals(Response::class, $method->getReturnType()->getName());
    }
}