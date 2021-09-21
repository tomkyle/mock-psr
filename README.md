<h1 align="center">PHP Package Boilerplate</h1>

<p align="center"> A template repository for PHP package.</p>

---



## Create new project


Either using `composer create-project` …

```bash
$ composer create-project tomkyle/boilerplate-php new-project  
```


… or using the traditional way using `git clone`:

```bash
$ git clone git@github.com:tomkyle/boilerplate-php.git
# or
$ git clone https://github.com/tomkyle/boilerplate-php.git
```



## Requirements and suggestions

- [psr/log](https://packagist.org/packages/psr/log) *PSR-3 Logger implementation*  – [Monolog Logger](https://github.com/Seldaek/monolog)
- [psr/cache](https://packagist.org/packages/psr/cache) *PSR-6 Cache Implementation – [Symfony Cache component](https://symfony.com/components/Cache)*
- [psr/http-factory](https://packagist.org/packages/psr/http-factory) *PSR-17 HTTP factory* implementation –Nyholm's [nyholm/psr7](nyholm/psr7) which (despite its name) provides the PSR-17 factories as well.
- [psr/http-client](https://packagist.org/packages/psr/http-client) *PSR-18 HTTP client* implementation – [Guzzle 7](https://packagist.org/packages/guzzlehttp/guzzle) via [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/) 


```bash
$ composer require monolog/monolog
$ composer require symfony/cache
$ composer require nyholm/psr7
$ composer require guzzlehttp/guzzle
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

