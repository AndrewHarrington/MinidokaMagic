<?php
//error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('vendor/autoload.php');
//require('model/functions.php');

//start the session
session_start();

$f3 = Base::instance();

$f3->route('GET|POST /', function (){
    $view = new Template();
    echo $view->render('view/registered-participants.html');
});

$f3->run();