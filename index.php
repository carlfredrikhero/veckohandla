<?php

require 'vendor/autoload.php';
require 'config.php';
require 'lib/mysql/class.db.php';

require 'models/items.php';

$db = new db("mysql:host=" . DB1_HOST . ";port=" . DB1_PORT . ";dbname=" . DB1_NAME . ';charset=UTF8', DB1_USER, DB1_PASS);

$app = new \Slim\Slim(array(
	'log.enabled' => true,
));

/* init secure session cookies */
$app->add(new \Slim\Middleware\SessionCookie(array(
    'secret' => 'HiMwyE#egRet25ifs!47+ka',
)));

$app->add(new \Slim\Middleware\ContentTypes());

$app->get('/', function() use ($app){
	
	// get latest date
	$date = date('Y-m-d');
	$human_date = date('j M, Y');
	$app->render('home.php', array(
		'date' => $date,
		'human_date' => $human_date,
	));
});

$app->get('/items', function() use ($app){
	$items = new Items();
	$date = $app->request()->get('date');
	$user_id = (int) $app->request()->get('user_id');
	$items->fetch(array(
		'date' => $date,
		'user_id' => $user_id,
	));

	$response = $app->response();
    $response['Content-Type'] = 'application/json';
    $response->status(200);
    $response->body($items->to_json());
});

$app->post('/items', function() use ($app){
	$data = $app->request()->getBody();

	$item = new Item($data);
	$result = $item->save();
	
	$response = $app->response();
    $response['Content-Type'] = 'application/json';
	
	if ($result){
		$response->status(200);
		$response->body($item->to_json());
	} else {
		$response->status(500);
		$response->body(json_encode($result));
	}
});

$app->put('/items/:id', function($id) use ($app){
	$response = $app->response();
    $response['Content-Type'] = 'application/json';
	
	$id = (int) $id;
	if (!$id){
		$response->status(500);
		$response->body(json_encode('Artikeln kunde inte sparas'));
	}
	
	$data = $app->request()->getBody();

	$item = new Item($data);
	$item->set_id($id);
	$result = $item->save();
	
	if ($result){
		$response->status(200);
		$response->body(json_encode($data));
	} else {
		$response->status(500);
		$response->body(json_encode('Artikeln kunde inte sparas'));
	}
});

$app->delete('/items/:id', function($id) use ($app){
	$response = $app->response();
    $response['Content-Type'] = 'application/json';
	
	$id = (int) $id;
	if (!$id){
		$response->status(500);
		$response->body(json_encode('Artikeln kunde inte tas bort 1'));
	}

	$item = new Item();
	$item->set_id($id);
	
	$item->remove();

	$response->status(200);
	$response->body(json_encode(array('id' => $id)));
});

$app->run();