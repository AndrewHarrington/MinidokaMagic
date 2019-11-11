<?php
/**
 * This file contains the validation for the user-form.html and participant-form.html
 * @file Validation.php
 */

/**
 * This function validates the input on the participant form
 * @return bool true if valid
 */
function validateParticipantForm(){
    global $f3;
    $isValid = true;

    if(!validName($f3->get('fname'))){
        $isValid = false;
        $f3->set("errors['fname']", "Not a valid first name, please reenter!");
    }

    if(!validName($f3->get('lname'))){
        $isValid = false;
        $f3->set("errors['lname']", "Not a valid last name, please reenter!");
    }

    if(!validPhone($f3->get('phone'))){
        $isValid = false;
        $f3->set("errors['phone']", "Not a valid phone number, please reenter!");
    }

    if(!validPhone($f3->get('ephone'))){
        $isValid = false;
        $f3->set("errors['ephone']", "Not a valid phone number, please reenter!");
    }

    if (!validAge($f3->get('age'))) {
        $isValid = false;
        $f3->set("errors['age']", "Please enter 1 or more.");
    }

    if(!validEmail($f3->get('email'))){
        $isValid = false;
        $f3->set("errors['email']", "Please enter a valid email.");
    }
    return $isValid;
}

/**
 * This function validates input on the add user form
 * @return bool returns true if valid
 */
function validateUserForm(){
    global $f3;
    $isValid = true;

    if(!validName($f3->get('fname'))){
        $isValid = false;
        $f3->set("errors['fname']", "Not a valid first name, please reenter!");
    }

    if(!validName($f3->get('lname'))){
        $isValid = false;
        $f3->set("errors['lname']", "Not a valid last name, please reenter!");
    }
    if(!validEmail($f3->get('email'))){
        $isValid = false;
        $f3->set("errors['email']", "Please enter a valid email.");
    }
    if(!validPhone($f3->get('phone'))){
        $isValid = false;
        $f3->set("errors['phone']", "Please enter a valid phone number");
    }
    //check username already exists
    global $db;
    $rows = $db->isValidUsername($f3->get('username'));
    if($rows > 0){
        $isValid = false;
        $f3->set("errors['username']", "Please enter a unique username.");
    }
    if(!validUsername($f3->get('username'))){
        $isValid = false;
        $f3->set("errors['username']", "Please enter a valid username.");
    }
    if(!validPassword($f3->get('password'))){
        $isValid = false;
        $f3->set("errors['password']", "Please enter a valid password");
    }
    return $isValid;
}

/**
 * This function checks if name only contains letter values and if input is empty
 * @param $name $name the name input
 * @return bool return true if valid
 */
function validName($name){
    return  !empty($name) && ctype_alpha($name);
}

/**
 * This function checks if the age input is empty and only uses numeric values of 0-9
 * @param $age $age the age input
 * @return bool true if valid
 */
function validAge($age){
    return !empty($age) && preg_match('/[0-9]/', $age);
}

/**
 * This function checks if the phone number uses numeric values and is 10 digits long
 * @param $phone $phone the phone input
 * @return bool true if valid
 */
function validPhone($phone){
    return (preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone)
            || preg_match("/^[0-9]{10}/", $phone)) && (mb_strlen((string)$phone) == 10 ||
            mb_strlen((string)$phone) == 13);
}

/**
 * This function checks if the email input a valid email  i.e. mail@mail.com
 * @param $email $email the email input
 * @return mixed
 */
function validEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * This function checks if the hotel input is not empty, optional input, will add to database if it checked
 * @param $hotel $hotel checkbox
 * @return bool return true if checked
 */
function validHotelRes($hotel){
    return !empty($hotel) && ctype_alnum($hotel);
}

/**
 * This function checks if the username input is not empty, and uses only alpha characters
 * @param $username $username the username
 * @return bool return true if valid
 */
function validUsername($username){
    return !empty($username) && ctype_alpha($username);
}

/**
 * This function checks if the password input is not empty
 * @param $password $password the password input
 * @return bool true if valid
 */
function validPassword($password){
    return  (!empty($password));
}