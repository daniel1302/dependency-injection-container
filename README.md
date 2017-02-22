Description
==============

To register service in container in container you must to use Object::Container addDefinition function. Function needs two parameters: first is service name, second anonymous function that is using to create the instance in first service call. Function will get instance of container when will be calling. Container is passing as function parameter. Function must return service instance.

Installation
===========
```bash
$ composer require fanta/DependencyInjectionContainer
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
