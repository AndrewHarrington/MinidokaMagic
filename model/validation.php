<?php

function validForm(){
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

function validName($name){
    return  !empty($name) && ctype_alpha($name);//preg_match('/[^A-Za-z]/', $name);
}

function validAge($age){
    return !empty($age) && preg_match('/[0-9]/', $age);
}

function validPhone($phone){
    return (preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone)
            || preg_match("/^[0-9]{10}/", $phone)) && (mb_strlen((string)$phone) == 10 ||
            mb_strlen((string)$phone) == 13);
}

function validEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validHotelRes($hotel){
    return !empty($hotel) && ctype_alnum($hotel);
}

function validUsername($username){
    return !empty($username) && ctype_alpha($username);
}

function validPassword($password){
    return  (!empty($password));
}