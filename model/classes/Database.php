<?php

/**
 *This class establishes a connection to a database and functions that query the database
 * Class Database
 * @author Andrew, Kat, Ryan and Zane
 * @copyright 2019
 */
class Database
{

    const IS_VALID_USER =
    "
        SELECT UUID
        FROM users
        WHERE username = :username AND password = :password
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
     * @param $fname
     * @param $lname
     * @param $phone
     * @param $emergency
     * @param $email
     * @param $age
     * @param $survivor
     * @param $hashotel
     * @param $prevattendences
     * @param $hotelRegId
     * @param $hotelName
     * @return mixed void
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

    public function getVolunteers(){
        $this->_getUsers->execute();
        return $this->_getUsers->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addUser($username, $password, $fname, $lname, $email, $phone, $editusers, $editreg, $editbudget){

        $this->_newUser->bindParam(':username', $username, PDO::PARAM_STR);
        $this->_newUser->bindParam(':password', $password, PDO::PARAM_STR);
        $this->_newUser->bindParam(':fname', $fname, PDO::PARAM_STR);
        $this->_newUser->bindParam(':lname', $lname, PDO::PARAM_STR);
        $this->_newUser->bindParam(':phone', $phone, PDO::PARAM_STR);
        $this->_newUser->bindParam(':email', $email, PDO::PARAM_STR);
        $this->_newUser->bindParam(':editusers', $editusers, PDO::PARAM_STR);
        $this->_newUser->bindParam(':editreg', $editreg, PDO::PARAM_STR);
        $this->_newUser->bindParam(':editbudget', $editbudget, PDO::PARAM_STR);

        $this->_newParticipant->execute();
        return $this->_newParticipant->fetchAll(PDO::FETCH_ASSOC);

    }

    public function removeUser($uuid){
        $this->_deactivateUser->bindParam(':uuid', $uuid, PDO::PARAM_INT);

        $this->_deactivateUser->execute();
        return $this->_deactivateUser->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isValidUser($username, $password){
        $this->_isValidUser->bindParam(':username', $username, PDO::PARAM_STR);
        $this->_isValidUser->bindParam(':password', $password, PDO::PARAM_STR);

        $this->_isValidUser->execute();
        return $this->_isValidUser->fetchAll(PDO::FETCH_ASSOC);
    }
}