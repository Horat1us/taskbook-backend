<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 2:16 PM
 */

namespace Horat1us\TaskBook\Tests;


use Horat1us\TaskBook\Application;
use Horat1us\TaskBook\CorsResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApplicationTest
 * @package Horat1us\TaskBook\Tests
 */
class ApplicationTest extends TestCase
{
    public function testOptionsRequest()
    {
        $request = new Request();
        $request->setMethod('OPTIONS');
        $application = new Application($request);
        $this->assertEquals($application->getRequest(), $request);
        $response = $application->run();
        $this->assertInstanceOf(CorsResponse::class, $response);
    }

    public function testInvalidUrl()
    {
        $request = new Request();
        $request->server->set('REQUEST_URI', '/controller-not-found');
        $application = new Application($request);
        $response = $application->run();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Not found", $response->getContent());
    }

    public function testControllerResponse()
    {
        $request = new Request();
        $request->setMethod('GET');
        $request->server->set("REQUEST_URI", '/tasks');
        $application = new Application($request);
        $response = $application->run();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('count', json_decode($response->getContent(), true));
    }
}