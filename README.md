<h1 align="center">tomkyle · Mock PSR</h1>

**Mock common PSR components in PhpUnit Tests.**
[![PHP Composer](https://github.com/tomkyle/mock-psr/actions/workflows/php.yml/badge.svg)](https://github.com/tomkyle/mock-psr/actions/workflows/php.yml)

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



## Development

### Run all tests

This packages has predefined test setups for code quality, code readability and unit tests. Check them out at the `scripts` section of **[composer.json](./composer.json)**.

```bash
$ composer test
# ... which includes
$ composer phpstan
$ composer phpcs
$ composer phpunit
```

### Unit tests

Default configuration is **[phpunit.xml.dist](./phpunit.xml.dist).** Create a custom **phpunit.xml** to apply your own settings. 
Also visit [phpunit.readthedocs.io](https://phpunit.readthedocs.io/) · [Packagist](https://packagist.org/packages/phpunit/phpunit)

```bash
$ composer phpunit
# ... or
$ vendor/bin/phpunit
```

### PhpStan

Default configuration is **[phpstan.neon.dist](./phpstan.neon.dist).** Create a custom **phpstan.neon** to apply your own settings. Also visit [phpstan.org](https://phpstan.org/) · [GitHub](https://github.com/phpstan/phpstan) · [Packagist](https://packagist.org/packages/phpstan/phpstan)

```bash
$ composer phpstan
# ... which includes
$ vendor/bin/phpstan analyse
```

### PhpCS

Default configuration is **[.php-cs-fixer.dist.php](./.php-cs-fixer.dist.php).** Create a custom **.php-cs-fixer.php** to apply your own settings. Also visit [cs.symfony.com](https://cs.symfony.com/) ·  [GitHub](https://github.com/FriendsOfPHP/PHP-CS-Fixer) · [Packagist](https://packagist.org/packages/friendsofphp/php-cs-fixer)

```bash
$ composer phpcs
# ... which aliases
$ vendor/bin/php-cs-fixer fix --verbose --diff --dry-run
```

Apply all CS fixes:

```bash
$ composer phpcs:apply
# ... which aliases 
$ vendor/bin/php-cs-fixer fix --verbose --diff
```

**On PHP 8.2, setting environment variable `PHP_CS_FIXER_IGNORE_ENV` is needed:**

```bash
$ PHP_CS_FIXER_IGNORE_ENV=1 composer phpcs
```



