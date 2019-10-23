<?php
//require_once('vendor/autoload.php');
////start the session
//session_start();
////error reporting
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
//
//$f3 = Base::instance();
//global $db;

//$f3->route('GET|POST /', function()
//{
//    session_destroy();
//    $view = new Template();
//    echo $view->render('view/login.html');
//});

//$f3->route('GET|POST /', function ($f3){
//    $db = new Database();
//    $data = $db->getRegistrationData();
//    $f3->set('registrations', $data);
//    $view = new Template();
//    echo $view->render('view/registered-participants.html');
//});


//error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('vendor/autoload.php');

$f3 = Base::instance();
global $db;

$f3->route('GET|POST /data', function ($f3) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $f3->reroute("/data");
    }
    $view = new Template();
    echo $view->render('view/login.html');
});

$f3->route('GET|POST /', function ($f3) {
    $db = new Database();
    $data = $db->getRegistrationData();
    $f3->set('registrations', $data);
    $view = new Template();
    echo $view->render('view/registered-participants.html');
});

$f3->route('GET|POST /new_user', function ($f3) {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $ephone = $_POST['ephone'];
        $age = $_POST['age'];
        $survivor = $_POST['survivor'];
        $attended = $_POST['prevattend'];

        $f3->set('fname', $fname);
        $f3->set('lname', $lname);
        $f3->set('phone', $phone);
        $f3->set('ephone', $phone);
        $f3->set('age', $age);
        $f3->set('survivor', $survivor);
        $f3->set('attended', $attended);

        if (validForm()) {
            //TODO
        }
    }

    $view = new Template();
    echo $view->render('view/participant_form.html');
});


$f3->run();