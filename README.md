<h1 align="center">tomkyle · Mock PSR</h1>

**Effortless PHPUnit mocks for common PSR interfaces – no more boilerplate, just clean tests.**
[![PHP Composer](https://github.com/tomkyle/mock-psr/actions/workflows/php.yml/badge.svg)](https://github.com/tomkyle/mock-psr/actions/workflows/php.yml)

---



## Installation

```bash
$ composer require --dev tomkyle/mock-psr
```

## Usage

This library provides two ways to use the PSR mocking functionality:

### Option 1: Individual Traits

Each PSR standard has its own trait that can be used independently in your test classes:

```php
<?php
use tomkyle\MockPsr\MockPsr6CacheTrait;
use tomkyle\MockPsr\MockPsr7MessagesTrait;
use tomkyle\MockPsr\MockPsr11ContainerTrait;
use tomkyle\MockPsr\MockPsr15RequestHandlerTrait;
use tomkyle\MockPsr\MockPsr17FactoriesTrait;
use tomkyle\MockPsr\MockPsr18ClientTrait;  

# Bonus
use tomkyle\MockPsr\MockPdoTrait;

class YourTest extends \PHPUnit\Framework\TestCase
{
    use MockPsr7MessagesTrait;
    use MockPsr11ContainerTrait;
    
    public function testSomething()
    {
        $response = $this->mockResponse(200, 'OK');
        $container = $this->mockContainer(['service' => 'value']);
    }
}
```

### Option 2: PsrMockFactory (Recommended)

For convenience, use the `PsrMockFactory` class which includes all traits and provides access to all mocking methods:

```php
<?php
use tomkyle\MockPsr\PsrMockFactory;

class YourTest extends \PHPUnit\Framework\TestCase
{
    public function testSomething()
    {
        $factory = new PsrMockFactory('test');
        
        $response = $factory->mockResponse(200, 'OK');
        $container = $factory->mockContainer(['service' => 'value']);
        $client = $factory->mockClient($response);
    }
}
```

## Examples

### PSR-7 Messages

```php
<?php
use tomkyle\MockPsr\PsrMockFactory;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	public function testSomething() 
	{
		$factory = new PsrMockFactory('test');

		// Psr\Http\Message\ServerRequestInterface
		$server_request = $factory->mockServerRequest();
		$attributes = array();
		$headers = array();
		$server_request = $factory->mockServerRequest($attributes, $headers);

		// Psr\Http\Message\UriInterface
		$uri = $factory->mockUri("https://test.com");

		// Psr\Http\Message\RequestInterface
		$request = $factory->mockRequest("GET", $uri);
		$request = $factory->mockRequest("GET", "/home");

		// Psr\Http\Message\StreamInterface
		$stream = $factory->mockStream("body string");

		// Psr\Http\Message\ResponseInterface
		$response = $factory->mockResponse(200, $stream);
		$response = $factory->mockResponse(404, "body string");
	}
}
```

### PSR-11 Container

Pass an optional array with things the Container has; calling *has* and *get* methods will behave like expected, including throwing `Psr\Container\NotFoundExceptionInterface`.

```php
<?php
use tomkyle\MockPsr\PsrMockFactory;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	public function testSomething() 
	{
		$factory = new PsrMockFactory('test');

		// Psr\Container\ContainerInterface
		$container = $factory->mockContainer();
		$container = $factory->mockContainer([
			'foo' => 'bar',
			'qux' => 'baz'        
		]);

		$container->has("foo"); // true
		$container->has("hello"); // false
		$container->get("hello"); // throws 'NotFoundExceptionInterface'
	}
}
```

### PSR-6 Cache

```php
<?php
use tomkyle\MockPsr\PsrMockFactory;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	public function testSomething() 
	{
		$factory = new PsrMockFactory('test');

		// Psr\Cache\CacheItemInterface
		$cache_item = $factory->mockCacheItem('cached value');
		$cache_item = $factory->mockCacheItem('value', ['isHit' => true]);
		$missing_item = $factory->mockMissingCacheItem('missing-key');

		// Psr\Cache\CacheItemPoolInterface
		$cache_pool = $factory->mockCacheItemPool();
		$cache_pool = $factory->mockCacheItemPool($cache_item);
		$cache_pool = $factory->mockCacheItemPool([
			'key1' => 'value1',
			'key2' => $cache_item
		]);
	}
}
```

### PSR-15 RequestHandler

Includes *MockPsr7MessagesTrait*

```php
<?php
use tomkyle\MockPsr\PsrMockFactory;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	public function testSomething() 
	{
		$factory = new PsrMockFactory('test');

		// Psr\Http\Server\RequestHandlerInterface
		$request_handler = $factory->mockRequestHandler();

		$response = $factory->mockResponse(404, "body string");
		$request_handler = $factory->mockRequestHandler( $response );
	}
}
```

### PSR-17 HTTP Factories

Includes *MockPsr7MessagesTrait*

```php
<?php
use tomkyle\MockPsr\PsrMockFactory;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	public function testSomething() 
	{
		$factory = new PsrMockFactory('test');

		// Psr\Http\Message\RequestFactoryInterface
		$request_factory = $factory->mockRequestFactory();

		$request = $factory->mockRequest();
		$request_factory = $factory->mockRequestFactory( $request );


		// Psr\Http\Message\ResponseFactoryInterface
		$response_factory = $factory->mockResponseFactory();

		$response = $factory->mockResponse(404, "body string");
		$response_factory = $factory->mockResponseFactory( $response );
	}
}
```

### PSR-18 HTTP Client

Includes *MockPsr7MessagesTrait*

```php
<?php
use tomkyle\MockPsr\PsrMockFactory;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	public function testSomething() 
	{
		$factory = new PsrMockFactory('test');

		// Psr\Http\Client\ClientInterface
		$client = $factory->mockClient();

		$response = $factory->mockResponse(404, "body string");
		$client = $factory->mockClient( $response );
	}
}
```

### PDO and PDOStatements

```php
<?php
use tomkyle\MockPsr\PsrMockFactory;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	public function testSomething() 
	{
		$factory = new PsrMockFactory('test');

		// \PDOStatement
		$execution_result = true;
		$stmt = $factory->mockPdoStatement($execution_result);
		$stmt = $factory->mockPdoStatement(true, array("foo" => "bar"));    

		// \PDO
		$pdo = $factory->mockPdo();
		$pdo = $factory->mockPdo(array("attribute" => \PDO::ATTR_ERRMODE));   
		
		$stmt_2 = $pdo->prepare("SELECT");
		// $stmt_2 will be a PDOStatement mock
	}
}
```



## Development

After cloning the repo, install dev dependencies like so:

```bash
$ composer install
$ npm install
```

This package has predefined test setups for code quality, code readability and unit tests. Check them out at the `scripts` section of **[package.json](./package.json)**. When working on this library, you basically only need:

```bash
$ npm run watch
```



