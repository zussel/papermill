<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\ContentTypes());

$app->contentType('application/json; charset=utf-8');

$app->get('/papers', function () use ($app) {
    $papers = array(
        array(
            'title' => 'Paper 1',
            'auhor' => 'Gabi Kühl',
            'published' => array(
                'by'=>'PaperPress',
                'year'=> 2011
            ),
            'type'=> 'PDF',
            'tags'=> array(
                'Paläontolgy',
                'Fossils',
                'Kambrium'
            )
        )
    );

    $app->response()->header('Content-Type', 'application/json; charset=utf-8');
    
    function encode_value(&$item, $key) {
        $item = utf8_encode($item);
    }
    array_walk_recursive($papers, 'encode_value');
    
    echo json_encode($papers);
});

$app->run();

?>
