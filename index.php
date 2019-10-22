<?php
require_once('vendor/autoload.php');
//start the session
session_start();
//error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

$f3 = Base::instance();
global $db;

//$f3->route('GET|POST /', function()
//{
//    session_destroy();
//    $view = new Template();
//    echo $view->render('view/login.html');
//});

$f3->route('GET|POST /', function ($f3){
    $db = new Database();
    $data = $db->getRegistrationData();
    $f3->set('registrations', $data);
    $view = new Template();
    echo $view->render('view/registered-participants.html');
});

$f3->run();