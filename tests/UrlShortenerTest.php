<?php

namespace madeny\UrlShortener\Test;

use Mockery;
use madeny\UrlShortener\Drivers\BaseDriver;
use madeny\UrlShortener\Drivers\Factory;
use madeny\UrlShortener\UrlShortener;

class UrlShortenerTest extends TestCase
{
    /**
     *  @test
     */
    public function it_calls_the_driver_to_shorten()
    {
        $factory   = Mockery::mock(Factory::class);
        $driver    = Mockery::mock(BaseDriver::class);
        $shortener = new UrlShortener($factory);

        $factory->shouldReceive('make')->with('driverName')->andReturn($driver);
        $shortener->setDriver('driverName');

        $driver->shouldReceive('shorten')->with('http://google.com')->andReturn('short');
        $url = $shortener->shorten('http://google.com');
        $this->assertEquals('short', $url);
    }

    /**
     *  @test
     */
    public function it_calls_the_driver_to_expand()
    {
        $factory   = Mockery::mock(Factory::class);
        $driver    = Mockery::mock(BaseDriver::class);
        $shortener = new UrlShortener($factory);

        $factory->shouldReceive('make')->with('driverName')->andReturn($driver);
        $shortener->setDriver('driverName');

        $driver->shouldReceive('expand')->with('http://google.com')->andReturn('short');
        $url = $shortener->expand('http://google.com');
        $this->assertEquals('short', $url);
    }

    /**
     *  @test
     */
    public function it_returns_new_instance_on_driver_hot_swap()
    {
        $this->assertInstanceOf(UrlShortener::class, \UrlShortener::driver('google'));
    }

    /**
     *  @test
     *  @expectedException \madeny\UrlShortener\Exceptions\InvalidResponseException
     */
    public function it_throws_invalid_response_exception_on_bad_response()
    {
        $factory   = Mockery::mock(Factory::class);
        $driver    = Mockery::mock(BaseDriver::class);
        $shortener = new UrlShortener($factory);

        $factory->shouldReceive('make')->with('driverName')->andReturn($driver);
        $shortener->setDriver('driverName');
        $driver->shouldReceive('expand')->with('http://google.com')->andThrow(new \GuzzleHttp\Exception\BadResponseException('e', Mockery::mock(\Psr\Http\Message\RequestInterface::class)));
        $shortener->expand('http://google.com');
    }

    /**
     *  @test
     *  @expectedException \madeny\UrlShortener\Exceptions\InvalidResponseException
     */
    public function it_throws_invalid_response_exception_on_invalid_api_response()
    {
        $factory   = Mockery::mock(Factory::class);
        $driver    = Mockery::mock(BaseDriver::class);
        $shortener = new UrlShortener($factory);

        $factory->shouldReceive('make')->with('driverName')->andReturn($driver);
        $shortener->setDriver('driverName');
        $driver->shouldReceive('expand')->with('http://google.com')->andThrow(new \Mremi\UrlShortener\Exception\InvalidApiResponseException);
        $shortener->expand('http://google.com');
    }
}
