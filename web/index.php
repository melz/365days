<?php
ini_set('display_errors', 0);
require_once __DIR__.'/../vendor/autoload.php';

/* Fixed Variables */
define('START_DATE', '2013-01-01');

/* Bootstrap */
$app = require __DIR__.'/../src/app.php';
//require __DIR__.'/../config/prod.php';
require __DIR__.'/../src/controllers.php';
$app->run();