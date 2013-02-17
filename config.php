<?php

define('ROOT', $_SERVER['ROOT']);

define("DB1_USER", $_SERVER['DB1_USER']);
define("DB1_PASS", $_SERVER['DB1_PASS']);
define("DB1_NAME", $_SERVER['DB1_NAME']);
define("DB1_HOST", $_SERVER['DB1_HOST']);

// Make sure errors are output to the screen
if ( $_SERVER['APPLICATION_ENV'] != 'production' ){
	ini_set('display_errors', '1');
}