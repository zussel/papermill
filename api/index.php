<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\ContentTypes());
$app->config('debug', true);

$app->contentType('application/json; charset=utf-8');

ORM::configure('sqlite:db/papermill.db');

require_once __DIR__ . '/models/exceptions.php';
require_once __DIR__ . '/models/models.php';
require_once __DIR__ . '/routes/routes.php';

$app->run();

?>
