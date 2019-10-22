<?php
class Database
{

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

    private $_getRegistrationData;
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
    }

    public function getRegistrationData(){
        $this->_getRegistrationData->execute();
        return $this->_getRegistrationData->fetchAll(PDO::FETCH_ASSOC);
    }
}