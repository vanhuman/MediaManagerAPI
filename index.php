<?php

require 'vendor/autoload.php';

use Helpers\ContainerHelper;
use Helpers\Routes;
use Slim\App;

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host'] = 'localhost';
$config['db']['user'] = 'user';
$config['db']['pass'] = 'password';
$config['db']['dbname'] = 'media_manager';

$config['pageSize'] = 50;

$app = new App(['settings' => $config]);
$container = $app->getContainer();

ContainerHelper::init($container);
Routes::init($app);

try {
    $app->run();
} catch (\Exception $ex) {
    echo 'Something went terribly wrong. O-o...';
}
