<?php


$app->get('/setup', function() use ($app) {
  setup_db();
});

