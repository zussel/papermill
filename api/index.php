<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\ContentTypes());

$app->contentType('application/json; charset=utf-8');

require_once __DIR__ . '/routes/routes.php';

$app->run();

?>
