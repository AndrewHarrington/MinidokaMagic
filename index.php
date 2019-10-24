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

$f3->route('GET|POST /', function ($f3) {
    $f3->set('hidden', "hidden");
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        var_dump($_POST);
        if($_POST['username'] == "volunteer" && $_POST['password'] == "MinidokaPilgrimage"){
            $f3->reroute("/registrations");
        }
        else{
            //ERROR OUT
            $f3->set('hidden', "");
        }
    }
    $view = new Template();
    echo $view->render('view/login.html');
});

$f3->route('GET|POST /registrations', function ($f3) {
    $db = new Database();
    $data = $db->getRegistrationData();
    $f3->set('registrations', $data);
    $view = new Template();
    echo $view->render('view/registered-participants.html');
});

$f3->route('GET|POST /budget-pdf', function (){
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $file_type=$_FILES['file']['type'];

        //validate that the file is a pdf
        if(isset($_POST["submit"])) {

            if($file_type=="application/pdf") {
                //echo "File is a PDF.";
                $uploadOk = 1;
            } else {
                echo "File is not a PDF.";
                $uploadOk = 0;
            }
        }

        //Check file name length < 23chars
        if(strlen($_FILES["file"]["name"]) > 23){
            echo "Sorry, file name too long. ";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            //save the pdf to an "uploads" folder
            if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                //reroute to a master page to select a pdf to view
                echo "Sorry, there was an error uploading your file.";
                //echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
            }
        }
    }
    $view = new Template();
    echo $view->render('view/budget-views/budget-upload.php');
});

$f3->route('GET /budget-view/@fileName', function (){
    $view = new Template();
    echo $view->render('view/budget-views/budget-view.php');
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
        $f3->set('ephone', $ephone);
        $f3->set('age', $age);
        $f3->set('survivor', $survivor);
        $f3->set('attended', $attended);

        if (validForm()) {
            echo "Hello World";
        }
    }

    $view = new Template();
    echo $view->render('view/participant_form.html');
});


$f3->run();