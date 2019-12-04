<?php
session_start();
require_once('vendor/autoload.php');
//error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('model/validation.php');

$f3 = Base::instance();
$db = new Database();

$f3->route('GET|POST /', function ($f3) {

    global $db;

    $f3->set('hidden', "hidden");

    //Store user info into session and validate if user is logged in
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $username = $_POST['username'];
        $password = $_POST['password'];

            $user = $db->isValidUser($username, $password);


            if ($user) {

                $_SESSION['fname'] = $user[0]['fname'];
                $_SESSION['lname'] = $user[0]['lname'];
                $_SESSION['admin'] = $user[0]['admin'];

                if ($user[0]['admin'] == 0) {

                    $f3->set('hidden', "");
                }

                $f3->reroute("/registrations");
            } else {

                //ERROR OUT
                $f3->set('hidden', "");
            }
        }
    $view = new Template();
    echo $view->render('view/login.html');
});

$f3->route('GET /logout', function  ($f3) {

    //clear session data
    session_unset();
    session_destroy();

   $f3->reroute('/');

});

$f3->route('GET|POST /registrations', function ($f3) {

    if((!isset($_SESSION['fname'])) || (!isset($_SESSION['lname'])) ||
        (!isset($_SESSION['admin']))) {

        $f3->reroute('/');
    };

    global $db;
    $data = $db->getRegistrationData();
    $f3->set('registrations', $data);

    $view = new Template();
    echo $view->render('view/registered-participants.html');
});

$f3->route('GET|POST /budget-pdf', function ($f3) {

    if((!isset($_SESSION['fname'])) || (!isset($_SESSION['lname'])) ||
        (!isset($_SESSION['admin']))) {

        $f3->reroute('/');
    };

    if($_SERVER['REQUEST_METHOD'] == "POST") {

        //if we are trying to delete a file
        if(isset($_POST['file'])) {

            // Check file exist or not
            if( file_exists('uploads/'.$_POST['file']) ) {

                // Remove file
                unlink('uploads/'.$_POST['file']);
            }
        }

        //if we are trying to upload a new file
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

    $dir    = '/home/minidoka/public_html/MinidokaMagic/uploads';
    $files = array_diff(scandir($dir), array('..', '.'));
    $f3->set("files", $files);

    $f3->set("isAdmin", $_SESSION['admin']);

    $view = new Template();
    echo $view->render('view/budget-views/budget-upload.php');
});

$f3->route('GET /budget-view/@fileName', function ($f3) {

    if((!isset($_SESSION['fname'])) || (!isset($_SESSION['lname'])) ||
        (!isset($_SESSION['admin']))) {

        $f3->reroute('/');
    };

    $view = new Template();
    echo $view->render('view/budget-views/budget-view.php');
});

$f3->route('GET|POST /reference-pdf', function ($f3) {

    if((!isset($_SESSION['fname'])) || (!isset($_SESSION['lname'])) ||
        (!isset($_SESSION['admin']))) {

        $f3->reroute('/');
    };

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        //if we are trying to delete a file
        if(isset($_POST['file'])){
            // Check file exist or not
            if( file_exists('reference-docs/'.$_POST['file']) ) {
                // Remove file
                unlink('reference-docs/'.$_POST['file']);
            }
        }

        //if we are uploading
        $target_dir = "reference-docs/";
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

    $dir    = '/home/minidoka/public_html/MinidokaMagic/reference-docs';
    $files = array_diff(scandir($dir), array('..', '.'));
    $f3->set("files", $files);

    $f3->set("isAdmin", $_SESSION['admin']);

    $view = new Template();
    echo $view->render('view/document-views/doc-upload.php');
});

$f3->route('GET /doc-view/@fileName', function ($f3) {

    if((!isset($_SESSION['fname'])) || (!isset($_SESSION['lname'])) ||
        (!isset($_SESSION['admin']))) {

        $f3->reroute('/');
    };

    $view = new Template();
    echo $view->render('view/document-views/doc-view.php');
});

$f3->route('GET|POST /new-participant', function ($f3) {

    if((!isset($_SESSION['fname'])) || (!isset($_SESSION['lname'])) ||
        (!isset($_SESSION['admin']))) {

        $f3->reroute('/');
    };

    if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST)) {

        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $ephone = $_POST['emergency'];

        //parse the age
        $age = $_POST['age'];
        $age = explode("-", $age);
        $age = 2019 - $age[0];
        if($_POST['survivor'][0]==1)
        {
            $survivor = 1;
        }else{
            $survivor = 0;
        }

        if($_POST['prevattendences'][0]==1)
        {
            $attended = 1;
        }else{
            $attended = 0;
        }
        if(isset($_POST['hasHotel']))
        {
            $_POST['hasHotel'] = 1;
        }
        $hasHotel = $_POST['hasHotel'];
        $hotelResID = $_POST['hotelResID'];
        $hotelName = $_POST['hotelName'];
        $filterPhone = filter_var($phone,FILTER_SANITIZE_NUMBER_INT);
        $stripPhone = str_replace("-","",$filterPhone);
        $phone = $stripPhone;

        $filterEphone = filter_var($ephone,FILTER_SANITIZE_NUMBER_INT);
        $stripEPhone = str_replace("-","",$filterEphone);
        $ephone = $stripEPhone;

        $f3->set('fname', $fname);
        $f3->set('lname', $lname);
        $f3->set('email', $email);
        $f3->set('phone', $phone);
        $f3->set('emergency', $ephone);
        $f3->set('age', $age);
        $f3->set('survivor', $survivor);
        $f3->set('prevattendences', $attended);
        $f3->set('hasHotel',$hasHotel);
        $f3->set('hotelResID', $hotelResID);
        $f3->set('hotelName', $hotelName);
        //var_dump($_POST);

        if (validateParticipantForm()) {

            global $db;
            $db->insertParticipant($fname, $lname, $phone, $ephone, $email, $age, $survivor, $hasHotel, $attended,
                $hotelResID, $hotelName);

            $f3->reroute('/registrations');
        }
    }
    $view = new Template();
    echo $view->render('view/participant-form.html');
});

$f3->route('GET|POST /update-participant', function ($f3){
    //check to see if they are logged in
    if((!isset($_SESSION['fname'])) || (!isset($_SESSION['lname'])) ||
        (!isset($_SESSION['admin']))) {
        $f3->reroute('/');
    };
    global $db;
    $id = $_POST['regID'];
    $f3->set('regID',$id);
    $data = $db->getRegistrant($id)[0];

    $hotelData = $db->getHotel($id)[0];

    if(!empty($id)) {
        $age = $f3->get('age', $data['age']);
        $f3->set('age', $age);
        $f3->set('fname', $data['fname']);
        $f3->set('lname', $data['lname']);
        $f3->set('email', $data['email']);
        $f3->set('phone', $data['phone']);
        $f3->set('emergency', $data['emergency']);
        $f3->set('age', $data['age']);
        $f3->set('survivor', $data['survivor']);
        $f3->set('attended', $data['prevattendences']);
        $f3->set('hasHotel', $data['hashotel']);

        $f3->set('hotelResID', $hotelData['hotelResID']);
        $f3->set('hotelName', $hotelData['hotelName']);
        $f3->set('cancelled', $data['cancelled']);
        $f3->set('update', true);
        $view = new Template();
        echo $view->render('view/participant-form.html');

    }
    //check to see if they have submitted an updated participant
    if(($_SERVER['REQUEST_METHOD'] == "POST") && ($_POST['isUpdate'] == 1)){

        if($_POST != $data)
        {
            $phone = $_POST['phone'];
            $filterPhone = filter_var($phone,FILTER_SANITIZE_NUMBER_INT);
            $stripPhone = str_replace("-","",$filterPhone);
            $phone = $stripPhone;

            $ePhone = $_POST['emergency'];
            $eFilterPhone = filter_var($ePhone,FILTER_SANITIZE_NUMBER_INT);
            $eStripPhone = str_replace("-","",$eFilterPhone);
            $ePhone = $eStripPhone;
            if($data['hashotel'] == 1)
            {
                $db->editParticipant($id ,$_POST['fname'],$_POST['lname'], $phone, $ePhone, $_POST['email'],
                    $_POST['age'], $_POST['survivor'][0],$data['hashotel'],$_POST['prevattendences'][0],$_POST['cancelled'][0]);
            }
            else{
                $db->editParticipant($id ,$_POST['fname'],$_POST['lname'],$phone, $ePhone, $_POST['email'],
                    $_POST['age'], $_POST['survivor'][0],$_POST['hasHotel'],$_POST['prevattendences'][0],$_POST['cancelled'][0]);

            }

        }
        //database interactions
        if((isset($_POST['hasHotel'])) && ($hotelData == null)){
            $_POST['hasHotel'] = 1;
            //update participant hashotel column
            $db->updateHotel($id,$_POST['hasHotel']);

            //insert hotel
            $db->insertHotel($id,$_POST['hotelName'] ,$_POST['hotelResID']);

        } elseif ((empty($_POST['hasHotel']) && ($hotelData != null))) {
            $_POST['hasHotel'] = 0;
            //update participant hashotel column
            $db->updateHotel($id, $_POST['hasHotel']);
            //remove hotel
            $db->removeHotel($id);

        } elseif(isset($_POST['hasHotel']) && ($_POST['hotelName'] != $hotelData['hotelName']) || ($_POST['hotelResID'])
                != $hotelData['hotelResID'])
        {
            $_POST['hasHotel'] = 1;
            $db->updateHotel($id,$_POST['hasHotel']);
            $db->editHotel($id,$_POST['hotelName'], $_POST['hotelResID']);
        }

        $f3->reroute('/registrations');
    }
});

$f3->route('GET|POST /add-volunteer', function ($f3) {
    if((!isset($_SESSION['fname'])) || (!isset($_SESSION['lname'])) ||
        (!isset($_SESSION['admin']))) {
        $f3->reroute('/');
    };
    if(!empty($_POST)) {

        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $admin = $_POST['admin'];
        $admin = $admin[0];
        $filterPhone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        $stripPhone = str_replace("-","", $filterPhone );
        $phone = $stripPhone;
        $f3->set('fname', $fname);
        $f3->set('lname', $lname);
        $f3->set('email', $email);
        $f3->set('phone', $phone);
        $f3->set('admin', $admin);
        $f3->set('username', $username);
        $f3->set('password', $password);

        if (validateUserForm()) {

            global $db;
            $db->addUser($username,$password,$fname,$lname,$email,$phone,$admin);
            $f3->reroute('/volunteers');
        }
    }
        $view = new Template();
        echo $view->render('view/user-form.html');
});

$f3->route('GET|POST /volunteers', function ($f3){
    if((!isset($_SESSION['fname'])) || (!isset($_SESSION['lname'])) ||
        (!isset($_SESSION['admin']))){
        $f3->reroute('/');
    };
    global $db;

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        //get the thing to be deleted
        $id = $_POST['uuid'];
        $db->removeUser($id);
    }

    //get the volunteers
    $volunteers = $db->getVolunteers();

    //set the volunteers to the hive
    $f3->set('volunteers', $volunteers);

    $view = new Template();
    echo $view->render('view/volunteers.html');
});

$f3->route('GET|POST /archive-view', function ($f3) {

    if((!isset($_SESSION['fname'])) || (!isset($_SESSION['lname'])) ||
        (!isset($_SESSION['admin']))) {

        $f3->reroute('/');
    };

    global $db;

    $archives= $db->viewArchives();
    $f3->set("archives", $archives);

    $f3->set("isAdmin", $_SESSION['admin']);

    $view = new Template();
    echo $view->render('view/archive-views/archive-view.html');
});

$f3->route('GET /archive-view/@archiveName', function ($f3) {

    if((!isset($_SESSION['fname'])) || (!isset($_SESSION['lname'])) ||
        (!isset($_SESSION['admin']))) {

        $f3->reroute('/');
    };

    global $db;

    $archive = $f3->get('PARAMS.archiveName');
    $f3->set("archive", $archive);
    $data = $db->getArchive($archive);
    $f3->set("registrations", $data);

    $view = new Template();
    echo $view->render('view/archive-views/archive-table.html');
});

$f3->run();