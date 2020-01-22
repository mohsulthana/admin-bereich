<?php
// Configuration for Slim Dependency Injection Container
$container = $app->getContainer();

// Flash messages
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// Monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// Using Twig as template engine
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('templates', [
        'cache' => false //'cache'
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};

// Doctrine configuration
$container['em'] = function ($c) {
    $settings = $c->get('settings');
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($settings['doctrine']['meta']['entity_path'], $settings['doctrine']['meta']['isDevMode']);

    //$config->setAutoGenerateProxyClasses(true);

    return \Doctrine\ORM\EntityManager::create($settings['doctrine']['connection'], $config);
};

// Custom 404 handler
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $body = $c->get('view')->fetch('website/pages/404.twig');
        $response = $response->withStatus(404);
        return $response->write($body);
    };
};

$container['phpErrorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $log = $c->get('logger');
        $log->error($exception);
    };
};

// Authentication Controller
$container['Gemueseeggli\Controller\AuthenticationController'] = function ($c) {
    return new Gemueseeggli\Controller\AuthenticationController($c);
};

// Pages Controller
$container['Gemueseeggli\Controller\PagesController'] = function ($c) {
    return new Gemueseeggli\Controller\PagesController($c);
};

// Admin Homepage Controller
$container['Gemueseeggli\Controller\AdminController'] = function ($c) {
    return new Gemueseeggli\Controller\AdminController($c);
};

// API Controller
$container['Gemueseeggli\Controller\ApiController'] = function ($c) {
    return new Gemueseeggli\Controller\ApiController($c);
};

