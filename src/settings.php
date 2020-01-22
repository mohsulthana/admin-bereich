<?php

/** @var \Dotenv\Dotenv $dotenv */
$dotenv = new Dotenv\Dotenv(__DIR__ . "/..", (getenv("TRAVIS") == true) ? ".env.example" : ".env");
$dotenv->load();
require_once(__DIR__ . '/../database/db_connector.php');

$isDev = getenv("PROD") == 'false';

return [
    'settings' => [
        // If you put the application in production, change it to false
        'displayErrorDetails' => $isDev,

        // Renderer settins: where are the templates???
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings: where are the logs???
        'logger' => [
            'name' => 'gemueseeggli',
            'path' => __DIR__ . '/../logs/app.log',
        ],

        // Doctrine settings
        'doctrine' => [
            'meta' => [
                'entity_path' => [
                    'database/model'
                ],
                'isDevMode' => true, //TODO: Check why does Productive Mode not work
            ],
            'connection' => [
                'driver'   => 'pdo_mysql',
                'host'     => getenv('DB_HOST'),
                'dbname'   => getenv('DB_NAME'),
                'user'     => getenv('DB_USER'),
                'password' => getenv('DB_PASS'),
                'charset' => 'utf8',
            ],
        ],
    ],
];
