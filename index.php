<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim(array(
	'log.enabled' => true,
));

/* init secure session cookies */
$app->add(new \Slim\Middleware\SessionCookie(array(
    'secret' => 'HiMwyE#egRet25ifs!47+ka',
)));

$app->add(new \Slim\Middleware\ContentTypes());

$app->get('/', function() use ($app){
	echo 'Hello Slim World!';
});

$app->run();