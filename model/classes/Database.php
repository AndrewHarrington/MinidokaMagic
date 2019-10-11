<?php
require("/home/minidoka/db_connection.php");
class Database
{

    const GET_REGISTRATION_DATA =
    "
          SELECT * 
          FROM registrations INNER JOIN hotelregistrations 
          WHERE registrations.regID = hotelregistrations.userID
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
        //$user = $_SERVER['USER'];
        //require_once("/home/$user/db_connection.php");

        // Make the connection
        try {
          $this -> _dbc = new PDO(DSN, DB_USER, DB_PASSWORD);
          echo "connected";
            //return $this ->_dbc;
        }catch (PDOException $ex){
            echo "FATAL FLAW FOUND<br>$ex<br><br><br><br>";
            echo $ex -> getMessage();
            return;
        }

        //$this->_getRegistrationData = $dbc->prepare(self::INSERT_MEMBER);
    }

    public function getRegistrationData(){
        $sql = self::GET_REGISTRATION_DATA;

        $statement = $this->_dbc->prepare($sql);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row;
//        $this->_getRegistrationData->execute();
//        return $this->_getRegistrationData->fetchAll(PDO::FETCH_ASSOC);
    }
}