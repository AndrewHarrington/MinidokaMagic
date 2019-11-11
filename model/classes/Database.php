<?php

/**
 *This class establishes a connection to a database and functions that query the database
 * Class Database
 * @author Andrew, Kat, Ryan and Zane
 * @copyright 2019
 */
class Database
{

    const IS_VALID_USERNAME =
    "
        SELECT *
        FROM users
        WHERE username = :username AND active = 1
    ";

    const IS_VALID_USER =
    "
        SELECT *
        FROM users
        WHERE username = :username AND password = :password AND active = 1
    ";

    const DEACTIVATE_USER =
    "
        UPDATE users
        SET active = 0
        WHERE UUID = :uuid
    ";

    const INSERT_NEW_USER =
    "
        INSERT INTO users
        (username, password, fname, lname, email, phone, editusers, editreg, editbudget)
        VALUES (:username, :password, :fname, :lname, :email, :phone, :editusers, :editreg, :editbudget)
    ";

    const GET_REGISTRATION_DATA_1 =
    "
          SELECT * 
          FROM registrations INNER JOIN hotelregistrations 
          WHERE registrations.regID = hotelregistrations.userID
          UNION
          SELECT regID, fname, lname, phone, emergency, email, age, survivor, hashotel, prevattendences, NULL as hotelRedPKID, NULL as userID, NULL as hotelResID, NULL as hotelName
          FROM registrations
          WHERE hashotel = false
    ";

    const NEW_PARTICIPANT =
    "
        INSERT 
        INTO registrations
          (fname, lname, phone, emergency, email, age, survivor, hashotel, prevattendences) 
        VALUES (:fname, :lname, :phone, :emergency, :email, :age, :survivor, :hashotel, :prevattendences);
        
        SELECT @@IDENTITY;
        
    ";

    const NEW_HOTEL_REG =
    "
        INSERT INTO hotelregistrations(userID, hotelResID, hotelName)
        VALUES (:id, :resId, :hotelName)
    ";

    const GET_VOLUNTEERS =
    "
        SELECT * FROM users
        WHERE active = 1
    ";

    private $_getRegistrationData;
    private $_newParticipant;
    private $_newHotelReg;
    private $_getUsers;
    private $_newUser;
    private $_deactivateUser;
    private $_isValidUser;
    private $_isValidUsername;
    private $_dbc;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * Connects to the database and stores the connection inside the class
     * @return PDO|void
     */
    public function connect(){
        $user = $_SERVER['USER'];
        require_once("/../home/minidoka/db_connection.php");
        // Make the connection
        try {
          $this -> _dbc = new PDO(DSN, DB_USER, DB_PASSWORD);
          //return $this ->_dbc;
        }catch (PDOException $ex){
            echo "FATAL FLAW FOUND<br>$ex<br><br><br><br>";
            echo $ex -> getMessage();
            return;
        }

        $this->_getRegistrationData = $this->_dbc->prepare(self::GET_REGISTRATION_DATA_1);
        $this->_newParticipant = $this->_dbc->prepare(self::NEW_PARTICIPANT);
        $this->_newHotelReg = $this->_dbc->prepare(self::NEW_HOTEL_REG);
        $this->_getUsers = $this->_dbc->prepare(self::GET_VOLUNTEERS);
        $this->_newUser = $this->_dbc->prepare(self::INSERT_NEW_USER);
        $this->_deactivateUser = $this->_dbc->prepare(self::DEACTIVATE_USER);
        $this->_isValidUser = $this->_dbc->prepare(self::IS_VALID_USER);
        $this->_isValidUsername = $this->_dbc->prepare(self::IS_VALID_USERNAME);

    }

    /**
     * Function to retrieve multiple participant details
     * @return mixed participant details
     */
    public function getRegistrationData(){
        $this->_getRegistrationData->execute();
        return $this->_getRegistrationData->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * This function adds a participant to the registered-participant into the database
     * @param $fname Database column participant first name
     * @param $lname Database column participant last name
     * @param $phone Database column participant phone number
     * @param $emergency Database column participant emergency number
     * @param $email Database column participant email
     * @param $age Database column participant age
     * @param $survivor Database column participant is a survivor or not
     * @param $hashotel Database column participant has a hotel reservation
     * @param $prevattendences Database column participant has attended pilgrimage before
     * @param $hotelRegId Database column participant hotel confirmation number
     * @param $hotelName Database column participant hotel name
     * @return mixed void Database column participant data
     */
    public function insertParticipant
        ($fname, $lname, $phone, $emergency, $email, $age, $survivor, $hashotel, $prevattendences,
         $hotelRegId, $hotelName){

        $this->_newParticipant->bindParam(':fname', $fname, PDO::PARAM_STR);
        $this->_newParticipant->bindParam(':lname', $lname, PDO::PARAM_STR);
        $this->_newParticipant->bindParam(':phone', $phone, PDO::PARAM_STR);
        $this->_newParticipant->bindParam(':emergency', $emergency, PDO::PARAM_STR);
        $this->_newParticipant->bindParam(':email', $email, PDO::PARAM_STR);
        $this->_newParticipant->bindParam(':age', $age, PDO::PARAM_INT);
        $this->_newParticipant->bindParam(':survivor', $survivor, PDO::PARAM_BOOL);
        $this->_newParticipant->bindParam(':hashotel', $hashotel, PDO::PARAM_BOOL);
        $this->_newParticipant->bindParam(':prevattendences', $prevattendences, PDO::PARAM_INT);

        $this->_newParticipant->execute();
        $id = $this->_newParticipant->fetchAll(PDO::FETCH_ASSOC);
        $id = $id['@@identity'];
        if($hashotel){
            $this->_newHotelReg->bindParam(':id', $id, PDO::PARAM_INT);
            $this->_newHotelReg->bindParam(':regId', $hotelRegId, PDO::PARAM_INT);
            $this->_newHotelReg->bindParam(':hotelName', $hotelName, PDO::PARAM_STR);

            $this->_newHotelReg->execute();
            return $this->_newHotelReg->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    /**
     * This function gets volunteers that are stored in the users table
     * @return mixed
     */
    public function getVolunteers(){
        $this->_getUsers->execute();
        return $this->_getUsers->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * This function adds users to the user table
     * @param $username Database column for user's username
     * @param $password Database column for user's password
     * @param $fname Database column for user's first name
     * @param $lname Database column for user's last name
     * @param $email Database column for user's email
     * @param $phone Database column for user's phone number
     * @param $editusers Database column for user's permission to update user table
     * @param $editreg Database column for user's permission to update registrations
     * @param $editbudget Database column for user's permission to update budget documents
     * @return mixed
     */
    public function addUser($username, $password, $fname, $lname, $email, $phone, $editusers, $editreg, $editbudget){

        $this->_newUser->bindParam(':username', $username, PDO::PARAM_STR);
        $this->_newUser->bindParam(':password', $password, PDO::PARAM_STR);
        $this->_newUser->bindParam(':fname', $fname, PDO::PARAM_STR);
        $this->_newUser->bindParam(':lname', $lname, PDO::PARAM_STR);
        $this->_newUser->bindParam(':phone', $phone, PDO::PARAM_STR);
        $this->_newUser->bindParam(':email', $email, PDO::PARAM_STR);
        $this->_newUser->bindParam(':editusers', $editusers, PDO::PARAM_BOOL);
        $this->_newUser->bindParam(':editreg', $editreg, PDO::PARAM_BOOL);
        $this->_newUser->bindParam(':editbudget', $editbudget, PDO::PARAM_BOOL);

        $this->_newUser->execute();
        return $this->_newUser->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * This function removes user's from the user table
     * @param $uuid Database column for deleting user id
     * @return mixed
     */
    public function removeUser($uuid){
        $this->_deactivateUser->bindParam(':uuid', $uuid, PDO::PARAM_INT);

        $this->_deactivateUser->execute();
        return $this->_deactivateUser->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * This function validates a user's login credentials
     * @param $username Database column to check if username in the table
     * @param $password Database column to check the user password
     * @return mixed
     */
    public function isValidUser($username, $password){
        $this->_isValidUser->bindParam(':username', $username, PDO::PARAM_STR);
        $this->_isValidUser->bindParam(':password', $password, PDO::PARAM_STR);

        $this->_isValidUser->execute();
        return $this->_isValidUser->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * This function validates that a new user will have a unique username
     * @param $username Database column to check if username in the table
     * @return mixed
     */
    public function isValidUsername($username){
        $this->_isValidUsername->bindParam(':username', $username, PDO::PARAM_STR);

        $this->_isValidUsername->execute();
        return $this->_isValidUsername->rowCount(PDO::FETCH_ASSOC);
    }
}