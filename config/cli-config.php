<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require 'vendor/autoload.php';

$settings = include 'src/settings.php';
$settings = $settings['settings']['doctrine'];

$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($settings['meta']['entity_path'], $settings['meta']['isDevMode']);

$em = \Doctrine\ORM\EntityManager::create($settings['connection'], $config);

return ConsoleRunner::createHelperSet($em);
