<?php

class Database {

    public $conn;

    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "hotel_db";

    public function __construct(){

        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->dbname
        );

        // Check connection
        if($this->conn->connect_error){
            die("Database Connection Failed: " . $this->conn->connect_error);
        }

        // Set charset (important)
        $this->conn->set_charset("utf8");
    }

}
