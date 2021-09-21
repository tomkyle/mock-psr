<h1 align="center">tomkyle · Mock PSR</h1>

<p align="center">Mock common PSR components in PhpUnit Tests.</p>

---



## Usage

```php
<?php
use tomkyle\MockPsr\MockPsr3ContainerTrait;
use tomkyle\MockPsr\MockPsr6CacheTrait;
use tomkyle\MockPsr\MockPsr7MessagesTrait;
use tomkyle\MockPsr\MockPsr15RequestHandlerTrait;
use tomkyle\MockPsr\MockPsr18ClientTrait;  
```

## Example

```php
<?php
use tomkyle\MockPsr\MockPsr7MessagesTrait;

class SomeUnitTest extends \PHPUnit\Framework\TestCase
{
    use MockPsr7MessagesTrait;
 
  	public function testSomething() 
    {
        // Psr\Http\Message\RequestInterface
      	$request = $this->mockRequest("GET", "/home");
    }
}


```



## Unit tests and development

1. Copy `phpunit.xml.dist` to `phpunit.xml` 
2. Run [PhpUnit](https://phpunit.de/) like this:

```bash
$ composer test
# or
$ vendor/bin/phpunit
```

And there's more in the `scripts` section of **composer.json**.

