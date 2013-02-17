<?php

require 'vendor/autoload.php';
require 'config.php';

$app = new \Slim\Slim(array(
	'log.enabled' => true,
));

/* init secure session cookies */
$app->add(new \Slim\Middleware\SessionCookie(array(
    'secret' => 'HiMwyE#egRet25ifs!47+ka',
)));

$app->add(new \Slim\Middleware\ContentTypes());

$app->get('/', function() use ($app){
	$app->render('home.php');
});

$app->get('/items', function() use ($app){
	//$items = Item::find_all();
	$items = array(
		array(
			'label' => 'Mjölk',
			'done' => false,
			'tag' => 'Mejerier',
			'id' => 1,
		),
	);

	$response = $app->response();
    $response['Content-Type'] = 'application/json';
    $response->status(200);
    $response->body(json_encode($items));
});

$app->get('/server', function() use ($app){
	echo '<pre>';
		var_dump($_SERVER);
	echo '</pre>';
});

$app->run();