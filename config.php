<?php


defined('ROOT') || define('ROOT', (getenv('ROOT') ? getenv('ROOT') : 'http://veckohanda.local'));

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));

defined('DB1_USER') || define('DB1_USER', (getenv('DB1_USER') ? getenv('DB1_USER') : 'root'));
defined('DB1_PASS') || define('DB1_PASS', (getenv('DB1_PASS') ? getenv('DB1_PASS') : 'root'));
defined('DB1_NAME') || define('DB1_NAME', (getenv('DB1_NAME') ? getenv('DB1_NAME') : 'veckohandla'));
defined('DB1_HOST') || define('DB1_HOST', (getenv('DB1_HOST') ? getenv('DB1_HOST') : 'localhost'));
defined('DB1_PORT') || define('DB1_PORT', (getenv('DB1_PORT') ? getenv('DB1_PORT') : '3306'));

defined('FB_APP_ID') || define('FB_APP_ID', (getenv('FB_APP_ID') ? getenv('FB_APP_ID') : ''));

// Make sure errors are output to the screen
if ( APPLICATION_ENV != 'production' ){
	ini_set('display_errors', '1');
}