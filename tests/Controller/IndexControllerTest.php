<?php

namespace App\Tests\Controller;

use App\Controller\IndexController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

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
        
        // Callable
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

    public function testIndexControllerServiceAvailable()
    {
        $client = static::createClient();
        $container = static::getContainer();
        
        // Verify the controller service is available in the container
        $this->assertTrue($container->has(IndexController::class));
        $controller = $container->get(IndexController::class);
        $this->assertInstanceOf(IndexController::class, $controller);
    }
}