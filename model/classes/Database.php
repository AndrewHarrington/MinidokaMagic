<?php

/**
 *This class establishes a connection to a database and functions that query the database
 * Class Database
 * @author Andrew, Kat, Ryan and Zane
 * @copyright 2019
 */
class Database
{
    const REMOVE_ARCHIVE =
    "
        DROP TABLE :archive
    ";

    const GET_ARCHIVE =
    "
        SET @query = 'SELECT * FROM :archive';
        PREPARE stmt FROM @query;
        EXECUTE stmt;
    ";

    const VIEW_ARCHIVES =
    "
        SELECT TABLE_NAME
        FROM information_schema.TABLES
        WHERE TABLE_NAME LIKE '____\_registrations%%%';
    ";

    const REVERT_ARCHIVE =
    "
        DROP TABLE registrations;
        SET @query = concat('RENAME TABLE ', :archive , ' TO registrations');
        PREPARE stmt FROM @query;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    ";

    const ARCHIVE_ALREADY_EXISTS =
    "
        SELECT count(*) AS 'count'
        FROM information_schema.TABLES 
        WHERE (TABLE_NAME = CONCAT(YEAR(CURDATE()), '_registrations'))
    ";

    const CREATE_NEW_REG_TABLE =
    "
        CREATE TABLE IF NOT EXISTS `registrations` (
        `regID` int(11) NOT NULL AUTO_INCREMENT,
        `fname` varchar(30) NOT NULL,
        `lname` varchar(30) NOT NULL,
        `phone` varchar(10) DEFAULT NULL,
        `emergency` varchar(10) DEFAULT NULL,
        `email` varchar(320) DEFAULT NULL,
        `age` int(11) NOT NULL,
        `survivor` tinyint(1) NOT NULL DEFAULT '0',
        `hashotel` tinyint(1) NOT NULL DEFAULT '0',
        `prevattendences` tinyint(1) NOT NULL DEFAULT '0',
        `cancelled` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`regID`)
        );
    ";

    const ARCHIVE_COPY =
    "
        SET @date = YEAR(CURDATE());
        SET @extension = '_registrations';
        SET @count = CONCAT('_', :count);
        SET @newName = CONCAT(@date, @extension, @count);
        SET @query = concat('RENAME TABLE registrations TO ', @newName);
        PREPARE stmt FROM @query;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    ";

    const ARCHIVE_TABLE =
    "
        SET @date = YEAR(CURDATE());
        SET @extension = '_registrations';
        SET @newName = CONCAT(@date, @extension);
        SET @query = concat('RENAME TABLE registrations TO ', @newName);
        PREPARE stmt FROM @query;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    ";

    const GET_HOTEL =
    "
        SELECT * 
        FROM hotelregistrations
        WHERE userID = :id
    ";

    const REMOVE_HOTEL =
    "
        DELETE FROM hotelregistrations
        WHERE userID = :id;
    
    ";

    const GET_REGISTRANT =
    "
       SELECT *
       FROM registrations
       WHERE regID = :id
    ";

    const UPDATE_HAS_HOTEL =
    "
        UPDATE registrations
        SET hashotel = :hashotel
        WHERE regid = :id
    ";

    const INSERT_HOTEL =
    "
        INSERT INTO hotelregistrations (userID, hotelResID, hotelName)
        VALUES(:id, :hotelResID, :hotelName);
        
    ";

    const UPDATE_HOTEL =
    "
        UPDATE hotelregistrations
        SET hotelResID = :resID, hotelName = :hotelName
        WHERE userID = :id;
    ";

    const EDIT_PARTICIPANT =
    "
        UPDATE registrations
        SET fname = :fname, lname = :lname, phone = :phone, emergency = :emergency, email = :email, age = :age, 
          survivor = :survivor, hashotel = :hashotel, prevattendences = :prevattendences, cancelled = :cancelled
        WHERE regID = :id
    ";

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
        (username, password, fname, lname, email, phone, admin)
        VALUES (:username, :password, :fname, :lname, :email, :phone, :admin)
    ";

    const GET_REGISTRATION_DATA =
    "
          SELECT * 
          FROM registrations INNER JOIN hotelregistrations 
          WHERE registrations.regID = hotelregistrations.userID
          UNION
          SELECT regID, fname, lname, phone, emergency, email, age, survivor, hashotel, prevattendences, registrations.cancelled, NULL as hotelRedPKID, NULL as userID, NULL as hotelResID, NULL as hotelName
          FROM registrations
          WHERE hashotel = false AND (SELECT count(*) from hotelregistrations where userID = regID) = 0
    ";

    const NEW_PARTICIPANT =
    "
        INSERT 
        INTO registrations
          (fname, lname, phone, emergency, email, age, survivor, hashotel, prevattendences) 
        VALUES (:fname, :lname, :phone, :emergency, :email, :age, :survivor, 0, :prevattendences);
    ";

    const NEW_PARTICIPANT_WITH_HOTEL_REG =
    "
        INSERT 
        INTO registrations
          (fname, lname, phone, emergency, email, age, survivor, hashotel, prevattendences) 
        VALUES (:fname, :lname, :phone, :emergency, :email, :age, :survivor, :hashotel, :prevattendences);
        
        INSERT INTO hotelregistrations(userID, hotelResID, hotelName)
        VALUES ((SELECT @@IDENTITY), :resId, :hotelName)
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
    private $_updateParticipant;
    private $_updateHotel;
    private $_updateHasHotel;
    private $_insertHotel;
    private $_getRegistrant;
    private $_removeHotel;
    private $_getHotel;
    private $_archiveAlreadyExists;
    private $_archiveTable;
    private $_archiveCopy;
    private $_createNewRegTable;
    private $_viewArchives;
    private $_getArchive;
    private $_removeArchive;
    private $_revertArchive;
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
        $user = $_SERVER['HTTP_HOST'];
        if($user == "minidokamagic.greenriverdev.com"){
            require_once("/../home/minidoka/db_connection.php");
        }
        else{
            require_once("/../home1/leahillh/db_connection.php");
        }
        // Make the connection
        try {
          $this -> _dbc = new PDO(DSN, DB_USER, DB_PASSWORD);
          //return $this ->_dbc;
        }catch (PDOException $ex){
            echo "FATAL FLAW FOUND<br>$ex<br><br><br><br>";
            echo $ex -> getMessage();
            return;
        }

        $this->_getRegistrationData = $this->_dbc->prepare(self::GET_REGISTRATION_DATA);
        $this->_newParticipant = $this->_dbc->prepare(self::NEW_PARTICIPANT);
        $this->_newHotelReg = $this->_dbc->prepare(self::NEW_PARTICIPANT_WITH_HOTEL_REG);
        $this->_getUsers = $this->_dbc->prepare(self::GET_VOLUNTEERS);
        $this->_newUser = $this->_dbc->prepare(self::INSERT_NEW_USER);
        $this->_deactivateUser = $this->_dbc->prepare(self::DEACTIVATE_USER);
        $this->_isValidUser = $this->_dbc->prepare(self::IS_VALID_USER);
        $this->_isValidUsername = $this->_dbc->prepare(self::IS_VALID_USERNAME);
        $this->_updateParticipant = $this->_dbc->prepare(self::EDIT_PARTICIPANT);
        $this->_updateHotel = $this->_dbc->prepare(self::UPDATE_HOTEL);
        $this->_updateHasHotel = $this->_dbc->prepare(self::UPDATE_HAS_HOTEL);
        $this->_insertHotel = $this->_dbc->prepare(self::INSERT_HOTEL);
        $this->_getRegistrant = $this->_dbc->prepare(self::GET_REGISTRANT);
        $this->_removeHotel = $this->_dbc->prepare(self::REMOVE_HOTEL);
        $this->_getHotel = $this->_dbc->prepare(self::GET_HOTEL);
        $this->_createNewRegTable = $this->_dbc->prepare(self::CREATE_NEW_REG_TABLE);
        $this->_archiveAlreadyExists = $this->_dbc->prepare(self::ARCHIVE_ALREADY_EXISTS);
        $this->_archiveTable = $this->_dbc->prepare(self::ARCHIVE_TABLE . self::CREATE_NEW_REG_TABLE);
        $this->_archiveCopy = $this->_dbc->prepare(self::ARCHIVE_COPY . self::CREATE_NEW_REG_TABLE);
        $this->_viewArchives = $this->_dbc->prepare(self::VIEW_ARCHIVES);
        $this->_getArchive = $this->_dbc->prepare(self::GET_ARCHIVE);
        $this->_removeArchive = $this->_dbc->prepare(self::REMOVE_ARCHIVE);
        $this->_revertArchive = $this->_dbc->prepare(self::REVERT_ARCHIVE);

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
     * @param $hotelResId Database column participant hotel confirmation number
     * @param $hotelName Database column participant hotel name
     * @return mixed void Database column participant data
     */
    public function insertParticipant
        ($fname, $lname, $phone, $emergency, $email, $age, $survivor, $hashotel, $prevattendences,
         $hotelResId, $hotelName){

        if(!$hashotel){
            $this->_newParticipant->bindParam(':fname', $fname, PDO::PARAM_STR);
            $this->_newParticipant->bindParam(':lname', $lname, PDO::PARAM_STR);
            $this->_newParticipant->bindParam(':phone', $phone, PDO::PARAM_STR);
            $this->_newParticipant->bindParam(':emergency', $emergency, PDO::PARAM_STR);
            $this->_newParticipant->bindParam(':email', $email, PDO::PARAM_STR);
            $this->_newParticipant->bindParam(':age', $age, PDO::PARAM_INT);
            $this->_newParticipant->bindParam(':survivor', $survivor, PDO::PARAM_BOOL);
            $this->_newParticipant->bindParam(':prevattendences', $prevattendences, PDO::PARAM_BOOL);

            $this->_newParticipant->execute();
            return $this->_newParticipant->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            $this->_newHotelReg->bindParam(':fname', $fname, PDO::PARAM_STR);
            $this->_newHotelReg->bindParam(':lname', $lname, PDO::PARAM_STR);
            $this->_newHotelReg->bindParam(':phone', $phone, PDO::PARAM_STR);
            $this->_newHotelReg->bindParam(':emergency', $emergency, PDO::PARAM_STR);
            $this->_newHotelReg->bindParam(':email', $email, PDO::PARAM_STR);
            $this->_newHotelReg->bindParam(':age', $age, PDO::PARAM_INT);
            $this->_newHotelReg->bindParam(':survivor', $survivor, PDO::PARAM_BOOL);
            $this->_newHotelReg->bindParam(':hashotel', $hashotel, PDO::PARAM_BOOL);
            $this->_newHotelReg->bindParam(':prevattendences', $prevattendences, PDO::PARAM_BOOL);
            $this->_newHotelReg->bindParam(':resId', $hotelResId, PDO::PARAM_STR);
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
     * @param $admin
     * @return mixed
     */
    public function addUser($username, $password, $fname, $lname, $email, $phone, $admin){

        $this->_newUser->bindParam(':username', $username, PDO::PARAM_STR);
        $this->_newUser->bindParam(':password', $password, PDO::PARAM_STR);
        $this->_newUser->bindParam(':fname', $fname, PDO::PARAM_STR);
        $this->_newUser->bindParam(':lname', $lname, PDO::PARAM_STR);
        $this->_newUser->bindParam(':phone', $phone, PDO::PARAM_STR);
        $this->_newUser->bindParam(':email', $email, PDO::PARAM_STR);
        $this->_newUser->bindParam(':admin', $admin, PDO::PARAM_BOOL);

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
        $this->_isValidUser->bindParam(':password',$password, PDO::PARAM_STR);

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

    /**
     * This function edits an existing participant in the database
     * @param $id Database column participant unique identifier
     * @param $fname Database column participant first name
     * @param $lname Database column participant last name
     * @param $phone Database column participant phone number
     * @param $emergency Database column participant emergency number
     * @param $email Database column participant email
     * @param $age Database column participant age
     * @param $survivor Database column participant is a survivor or not
     * @param $hashotel Database column participant has a hotel reservation
     * @param $prevattendences Database column participant has attended pilgrimage before
     * @param $cancelled Database column participant has cancelled their attendence
     * @return mixed void Database column participant data
     */
    public function editParticipant
    ($id, $fname, $lname, $phone, $emergency, $email, $age, $survivor, $hashotel, $prevattendences, $cancelled){

        $this->_updateParticipant->bindParam(':id', $id, PDO::PARAM_INT);
        $this->_updateParticipant->bindParam(':fname', $fname, PDO::PARAM_STR);
        $this->_updateParticipant->bindParam(':lname', $lname, PDO::PARAM_STR);
        $this->_updateParticipant->bindParam(':phone', $phone, PDO::PARAM_STR);
        $this->_updateParticipant->bindParam(':emergency', $emergency, PDO::PARAM_STR);
        $this->_updateParticipant->bindParam(':email', $email, PDO::PARAM_STR);
        $this->_updateParticipant->bindParam(':age', $age, PDO::PARAM_INT);
        $this->_updateParticipant->bindParam(':survivor', $survivor, PDO::PARAM_BOOL);
        $this->_updateParticipant->bindParam(':hashotel', $hashotel, PDO::PARAM_BOOL);
        $this->_updateParticipant->bindParam(':prevattendences', $prevattendences, PDO::PARAM_BOOL);
        $this->_updateParticipant->bindParam(':cancelled', $cancelled, PDO::PARAM_BOOL);

        $this->_updateParticipant->execute();
        return $this->_updateParticipant->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * This Function updates a users hotel information
     * @param $userID - The id of the user
     * @param $hotelName - The new name of the hotel
     * @param $hotelResID - The new reservation number for that user
     * @return mixed
     */
    public function editHotel
    ($userID, $hotelName, $hotelResID){
        $this->_updateHotel->bindParam(':id', $userID, PDO::PARAM_INT);
        $this->_updateHotel->bindParam(':resID', $hotelResID, PDO::PARAM_STR);
        $this->_updateHotel->bindParam(':hotelName', $hotelName, PDO::PARAM_STR);

        $this->_updateHotel->execute();
        return $this->_updateHotel->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateHotel($regID,$hashotel) {
        $this->_updateHasHotel->bindParam(':id',$regID,PDO::PARAM_INT);
        $this->_updateHasHotel->bindParam(':hashotel',$hashotel,PDO::PARAM_BOOL);

        $this->_updateHasHotel->execute();
        return $this->_updateHasHotel->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * This Function inserts a users hotel information
     * @param $userID - The id of the user
     * @param $hotelName - The new name of the hotel
     * @param $hotelResID - The new reservation number for that user
     * @return mixed
     */
    public function insertHotel
    ($userID, $hotelName, $hotelResID){
        $this->_insertHotel->bindParam(':id', $userID, PDO::PARAM_INT);
        $this->_insertHotel->bindParam(':hotelResID', $hotelResID, PDO::PARAM_STR);
        $this->_insertHotel->bindParam(':hotelName', $hotelName, PDO::PARAM_STR);

        $this->_insertHotel->execute();
        return $this->_insertHotel->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets a registrant's data based on their id
     * @param $id - The unique identifier for the registrant
     * @return mixed - All of that registrant's data
     */
    public function getRegistrant($id){
        $this->_getRegistrant->bindParam(':id', $id, PDO::PARAM_INT);

        $this->_getRegistrant->execute();
        return $this->_getRegistrant->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Removes a registrant's hotel data based on their id
     * @param $userID - The unique identifier for the registrant
     * @return mixed
     */
    public function removeHotel($userID){
        $this->_removeHotel->bindParam(':id', $userID, PDO::PARAM_INT);

        $this->_removeHotel->execute();
        return $this->_removeHotel->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets a registrant's hotel data based on their id
     * @param $id - The unique identifier for the registrant
     * @return mixed - All of that registrant's hotel data
     */
    public function getHotel($id){
        $this->_getHotel->bindParam(':id', $id, PDO::PARAM_INT);

        $this->_getHotel->execute();
        return $this->_getHotel->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Archives all registrations for the year
     */
    public function archiveRegistrants(){
        //check to see if we need to change the archive
        $this->_archiveAlreadyExists->execute();
        $count = $this->_archiveAlreadyExists->fetchAll(PDO::FETCH_ASSOC)[0]['count'];

        //prepare to archive

        //if we need to copy
        if($count != "0"){
            $this->_archiveCopy->bindParam(':count', $count, PDO::PARAM_STR);
            $this->_archiveCopy->execute();
        }
        //we dont need to copy
        else{
            $this->_archiveTable->execute();
        }

    }

    public function viewArchives(){
        $this->_viewArchives->execute();
        return $this->_viewArchives->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArchive($archive){
        $sql = "SELECT * FROM $archive";
        $this->_getArchive = $this->_dbc->prepare($sql);

        $this->_getArchive->execute();
        return $this->_getArchive->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeArchive($archive){
        $this->_removeArchive->bindParam(":archive", $archive);
        $this->_removeArchive->execute();
        return $this->_removeArchive->fetchAll(PDO::FETCH_ASSOC);
    }

    public function revertArchive($archive){
        var_dump($archive);
        $this->_revertArchive->bindParam(":archive", $archive, PDO::PARAM_STR);
        $this->_revertArchive->execute();
        return $this->_revertArchive->fetchAll(PDO::FETCH_ASSOC);
    }
}