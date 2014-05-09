<?php
require 'vendor/autoload.php';

require 'middleware/JWTAuthMiddleware.php';

$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\ContentTypes());
$app->add(new \JWTAuthMiddleware());
$app->config('debug', true);

$app->contentType('application/json; charset=utf-8');

ORM::configure('sqlite:db/papermill.db');

/*
 * load config
 */
$GLOBALS['config'] = include __DIR__ . '/config/config.php';

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/exceptions.php';
require_once __DIR__ . '/models/models.php';
require_once __DIR__ . '/routes/routes.php';

$app->run();

?>
