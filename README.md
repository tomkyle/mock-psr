<h1 align="center">tomkyle · Mock PSR</h1>

<p align="center">Mock common PSR components in PhpUnit Tests.</p>

---



## Installation

```bash
$ composer require --dev tomkyle/mock-psr
```

## Usage

```php
<?php
use tomkyle\MockPsr\MockPsr11ContainerTrait;
use tomkyle\MockPsr\MockPsr6CacheTrait;
use tomkyle\MockPsr\MockPsr7MessagesTrait;
use tomkyle\MockPsr\MockPsr15RequestHandlerTrait;
use tomkyle\MockPsr\MockPsr18ClientTrait;  

# Bonus
use tomkyle\MockPsr\MockPdoTrait;
```

## Examples

### PSR-7 Messages

```php
<?php
use tomkyle\MockPsr\MockPsr7MessagesTrait;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	use MockPsr7MessagesTrait;

	public function testSomething() 
	{
		// Psr\Http\Message\ServerRequestInterface
		$server_request = $this->mockServerRequest();
		$attributes = array();
		$headers = array();
		$server_request = $this->mockServerRequest($attributes, $headers);

		// Psr\Http\Message\UriInterface
		$uri = $this->mockUri("https://test.com");

		// Psr\Http\Message\RequestInterface
		$request = $this->mockRequest("GET", $uri);
		$request = $this->mockRequest("GET", "/home");

		// Psr\Http\Message\StreamInterface
		$stream = $this->mockStream("body string");

		// Psr\Http\Message\ResponseInterface
		$response = $this->mockResponse(200, $stream);
		$response = $this->mockResponse(404, "body string");
	}
}
```

### PSR-11 Container

Pass an optional array with things the Container has; calling *has* and *get* methods will behave like expected, including throwing `Psr\Container\NotFoundExceptionInterface`.

```php
<?php
use tomkyle\MockPsr\MockPsr11ContainerTrait;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	use MockPsr11ContainerTrait;

	public function testSomething() 
	{
		// Psr\Container\ContainerInterface
		$container = $this->mockContainer();
		$container = $this->mockContainer([
			'foo' => 'bar',
			'qux' => 'baz'        
		]);

		$container->has("foo"); // true
		$container->has("hello"); // false
		$container->get("hello"); // throws 'NotFoundExceptionInterface'
	}
}
```

### PSR-15 RequestHandler

Includes *MockPsr7MessagesTrait*

```php
<?php
use tomkyle\MockPsr\MockPsr15RequestHandlerTrait;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	use MockPsr15RequestHandlerTrait;

	public function testSomething() 
	{
		// Psr\Http\Server\RequestHandlerInterface
		$request_handler = $this->mockRequestHandler();

		$response = $this->mockResponse(404, "body string");
		$request_handler = $this->mockRequestHandler( $response );
	}
}
```

### PSR-17 HTTP Factories

Includes *MockPsr7MessagesTrait*

```php
<?php
use tomkyle\MockPsr\MockPsr17FactoriesTrait;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	use MockPsr17FactoriesTrait;

	public function testSomething() 
	{
		// Psr\Http\Message\RequestFactoryInterface
		$request_factory = $this->mockRequestFactory();

		$request = $this->mockRequest();
		$request_factory = $this->mockRequestFactory( $request );


		// Psr\Http\Message\ResponseFactoryInterface
		$response_factory = $this->mockResponseFactory();

		$response = $this->mockResponse(404, "body string");
		$response_factory = $this->mockResponseFactory( $response );
	}
}
```

### PSR-18 HTTP Client

Includes *MockPsr7MessagesTrait*

```php
<?php
use tomkyle\MockPsr\MockPsr18ClientTrait;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	use MockPsr18ClientTrait;

	public function testSomething() 
	{
		// Psr\Http\Client\ClientInterface
		$client = $this->mockClient();

		$response = $this->mockResponse(404, "body string");
		$client = $this->mockClient( $response );
	}
}
```

### PDO and PDOStatements

```php
<?php
use tomkyle\MockPsr\MockPdoTrait;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
	use MockPdoTrait;

	public function testSomething() 
	{
		// \PDOStatement
		$execution_result = true;
    $stmt = $this->mockPdoStatement($execution_result);
    $stmt = $this->mockPdoStatement(true, array("foo" => "bar"));    

    // \PDO
    $pdo = $this->mockPdo();
    $pdo = $this->mockPdo($stmt);   
    
    $stmt_2 = $pdo->prepare("SELECT");
    $stmt_2 == $stmt
	}
}
```



## Unit tests and development

Run [PhpUnit](https://phpunit.de/) like this:

```bash
$ composer test
# or
$ vendor/bin/phpunit
```

And there's more in the `scripts` section of **composer.json**.

