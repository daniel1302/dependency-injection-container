Description
==============

To register service in container in container you must to use Object::Container addDefinition function. Function needs two parameters: first is service name, second anonymous function that is using to create the instance in first service call. Function will get instance of container when will be calling. Container is passing as function parameter. Function must return service instance.

Installation
===========
```bash
$ composer require vallheru/container
$ composer update
```

Simple Example
=============

````php
<?php
use Fanta\DependencyInjectionContainer as Container;
use Config\Config;
use Zend\Zend_Db;
use \TwigAdapter;
use Utils\Pdf;

Container::addDefinition('config', function() {
    $config = new Config();
    $config->load('config.ini.php');

    return $config;
});


Container::addDefinition('db', function(Container $container) {
    $config = $container
        ->get('config')
        ->get('database');

    return Zend_Db::factory('PDO_Mysql', array(
        'host'      => $config['host'],
        'dbname'    => $config['dbname'],
        'username'  => $config['username'],
        'password'  => $config['password'],
        'charset'   => $config['charset']
    ));
});

Container::addDefinition('twig', function(Container $container) {
    $config = $container
        ->get('config')
        ->get('twig');

    $twig = new TwigAdapter($config['tplpath'], $config['cachepath']);
    return $twig;
});

Container::addDefinition('pdf', function() {
    $pdf = new Pdf();
    return $pdf;
});
````


What is shared and not shared service
=====================================
- **Shared** service causes that in application exists only one instance of service.
When you call Container::get method you get same instance every time.

- **Not Shared** service causes that every time when you call Container::get method, you get new instance of service

Register not shared service
============================
```php
Container::addDefinition('serviceA', function() {
    $service = new ServiceA();
    $service->init(rand(1, 9999));
    
    return $service;
}, ['shared' => false]);

```