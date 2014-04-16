<?php


$app->get('/setup', function() use ($app) {
  setup_db();
});

$app->get('/clear', function() use ($app) {
  clear_tables();
});

$app->get('/drop', function() use ($app) {
  drop_db();
});

